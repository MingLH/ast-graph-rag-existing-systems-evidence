<?php
declare(strict_types=1);

require __DIR__ . '/../runtime-labs/mapin-l12/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use Mapin\BuildRunner;
use Mapin\Extract\Contracts\ExtractionContext;
use Mapin\Store\SqliteStore;

$root = realpath(__DIR__ . '/mapin-l12-framework-fixture');

if ($root === false) {
    fwrite(STDERR, "Fixture directory missing.\n");
    exit(1);
}

// Load independent fixture classes.
require_once $root . '/app/FrameworkPilot.php';
require_once $root . '/app/Services/UserService.php';
require_once $root . '/app/Http/Controllers/UserController.php';

// Use genuine Laravel 12 router and container objects.
$container = new Container();
$events = new Dispatcher($container);
$router = new Router($events, $container);

// Register routes without executing the controllers.
$router->get(
    '/users',
    [\App\Http\Controllers\UserController::class, 'show']
);

$router->resource(
    'resources',
    \App\Http\Controllers\UserController::class
);

$router->get(
    '/fyp-greeting',
    [\FypPilot\GreetingController::class, 'show']
);

// Register a genuine interface-to-class binding.
$container->bind(
    \FypPilot\Greeter::class,
    \FypPilot\FriendlyGreeter::class
);

$db = __DIR__
    . '/../evidence/test-results/mapin-l12-framework-pilot.sqlite';

$context = new ExtractionContext(
    $root,
    booted: true,
    router: $router,
    container: $container
);

$report = (new BuildRunner())->run(
    root: $root,
    storagePath: $db,
    projectPaths: ['app', 'routes'],
    vendorPaths: [],
    exclude: [],
    full: true,
    ctx: $context
);

$pdo = (new SqliteStore($db))->pdo();

$sql = '
    SELECT
        e.type,
        src.key AS source,
        dst.key AS destination,
        e.resolution,
        e.confidence,
        e.meta
    FROM edges e
    JOIN nodes src ON src.id = e.from_id
    JOIN nodes dst ON dst.id = e.to_id
    WHERE e.type IN ("routes_to", "binds")
       OR (
           e.type = "calls"
           AND src.key LIKE "method:FypPilot%"
       )
    ORDER BY e.type, src.key, dst.key
';

$edges = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$checkSql = '
    SELECT COUNT(*)
    FROM edges e
    JOIN nodes src ON src.id = e.from_id
    JOIN nodes dst ON dst.id = e.to_id
    WHERE e.type = ?
      AND src.key = ?
      AND dst.key = ?
';

$statement = $pdo->prepare($checkSql);

$expected = [
    'direct_route_to_controller' => [
        'routes_to',
        'route:GET /users',
        'method:App\Http\Controllers\UserController::show'
    ],
    'resource_show_route' => [
        'routes_to',
        'route:GET /resources/{resource}',
        'method:App\Http\Controllers\UserController::show'
    ],
    'greeting_route_to_controller' => [
        'routes_to',
        'route:GET /fyp-greeting',
        'method:FypPilot\GreetingController::show'
    ],
    'interface_to_implementation' => [
        'binds',
        'class:FypPilot\Greeter',
        'class:FypPilot\FriendlyGreeter'
    ],
    'controller_to_implementation_method' => [
        'calls',
        'method:FypPilot\GreetingController::show',
        'method:FypPilot\FriendlyGreeter::greet'
    ]
];

$checks = [];

foreach ($expected as $name => $arguments) {
    $statement->execute($arguments);
    $checks[$name] = (int) $statement->fetchColumn() > 0;
}

$result = [
    'framework' => 'Laravel 12',
    'mode' => 'registered_router_and_container',
    'report' => $report->toArray(),
    'registered_routes' => count($router->getRoutes()),
    'checks' => $checks,
    'selected_edges' => $edges,
];

echo json_encode(
    $result,
    JSON_PRETTY_PRINT
        | JSON_UNESCAPED_SLASHES
        | JSON_THROW_ON_ERROR
), PHP_EOL;

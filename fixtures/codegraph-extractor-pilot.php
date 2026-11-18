<?php

declare(strict_types=1);

require __DIR__ . '/../runtime-labs/codegraph-laravel/vendor/autoload.php';

use Maarheeze\CodeGraph\Laravel\Extractors\RouteExtractor;
use Maarheeze\CodeGraph\Laravel\Extractors\EloquentRelationExtractor;
use Maarheeze\CodeGraph\Laravel\Extractors\ServiceProviderBindingExtractor;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;

$cases = [
    'R01_direct_route' => [
        RouteExtractor::class,
        <<<'CODE'
<?php
Route::get('/users', [UserController::class, 'index']);
CODE
    ],
    'R02_resource_route' => [
        RouteExtractor::class,
        <<<'CODE'
<?php
Route::resource('users', UserController::class);
CODE
    ],
    'R03_imported_controller' => [
        RouteExtractor::class,
        <<<'CODE'
<?php
use App\Http\Controllers\UserController;
Route::get('/users', [UserController::class, 'index']);
CODE
    ],
    'E01_has_many' => [
        EloquentRelationExtractor::class,
        <<<'CODE'
<?php
namespace App\Models;
class User {
    public function posts() {
        return $this->hasMany(Post::class);
    }
}
CODE
    ],
    'E02_morph_to' => [
        EloquentRelationExtractor::class,
        <<<'CODE'
<?php
namespace App\Models;
class Activity {
    public function subject() {
        return $this->morphTo();
    }
}
CODE
    ],
    'E03_two_classes' => [
        EloquentRelationExtractor::class,
        <<<'CODE'
<?php
namespace App\Models;
class First extends Model {}
class Second extends Model {
    public function posts() {
        return $this->hasMany(Post::class);
    }
}
CODE
    ],
    'B01_container_binding' => [
        ServiceProviderBindingExtractor::class,
        <<<'CODE'
<?php
namespace App\Providers;
class AppServiceProvider {
    public function register() {
        $this->app->bind('Contract', 'Implementation');
    }
}
CODE
    ],
    'B02_unrelated_bind' => [
        ServiceProviderBindingExtractor::class,
        <<<'CODE'
<?php
namespace App\Services;
class RuleBuilder {
    public function make() {
        $this->builder->bind('A', 'B');
    }
}
CODE
    ],
];

$parser = (new ParserFactory())->createForHostVersion();
$results = [];

foreach ($cases as $id => [$visitorClass, $source]) {
    $ast = $parser->parse($source);
    $visitor = new $visitorClass('fixture.php', $source);

    $traverser = new NodeTraverser();
    $traverser->addVisitor($visitor);
    $traverser->traverse($ast ?? []);

    $results[$id] = array_map(
        static fn ($edge): array => [
            'kind' => $edge->kind,
            'source' => $edge->sourceFullyQualifiedName,
            'destination' => $edge->destinationFullyQualifiedName,
            'line' => $edge->line,
            'metadata' => $edge->metadata,
        ],
        $visitor->edges()
    );
}

echo json_encode(
    $results,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
), PHP_EOL;

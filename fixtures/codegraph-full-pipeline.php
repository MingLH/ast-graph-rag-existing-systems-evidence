<?php

declare(strict_types=1);

require __DIR__ . '/../runtime-labs/codegraph-laravel/vendor/autoload.php';

use Maarheeze\CodeGraph\CodeGraph;
use Maarheeze\CodeGraph\Laravel\LaravelPlugin;

$projectRoot = __DIR__ . '/codegraph-full-pipeline';

$graph = new CodeGraph(
    $projectRoot,
    ':memory:',
    ['app', 'routes']
);

$graph->registerPlugin(new LaravelPlugin());

$indexStats = $graph->index();

$pdo = $graph->getStorage()->pdo();

$statement = $pdo->query(
    'SELECT kind, src_fqn, dst_fqn, file, line, metadata
     FROM edges
     ORDER BY file, line, kind'
);

$edges = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(
    [
        'stats' => $graph->stats(),
        'edges' => $edges,
    ],
    JSON_PRETTY_PRINT
        | JSON_UNESCAPED_SLASHES
        | JSON_THROW_ON_ERROR
), PHP_EOL;

<?php
declare(strict_types=1);

require __DIR__ . '/../runtime-labs/codegraph-laravel/vendor/autoload.php';

use Maarheeze\CodeGraph\CodeGraph;
use Maarheeze\CodeGraph\Laravel\LaravelPlugin;

// Locate the existing BookStack repository.
$bookstack = realpath(__DIR__ . '/../../BookStack');

if (
    $bookstack === false ||
    !is_dir($bookstack . '/app') ||
    !is_dir($bookstack . '/routes')
) {
    fwrite(STDERR, "BookStack directory not found.\n");
    exit(1);
}

// Create an in-memory graph. No BookStack database is used.
$graph = new CodeGraph(
    $bookstack,
    ':memory:',
    ['app', 'routes']
);

// Enable the Laravel-specific extraction visitors.
$graph->registerPlugin(new LaravelPlugin());

// Parse and index the selected PHP source files.
$indexStats = $graph->index();

$pdo = $graph->getStorage()->pdo();

// Count edges by relationship type.
$kinds = $pdo->query(
    'SELECT kind, COUNT(*) AS total
     FROM edges
     GROUP BY kind
     ORDER BY total DESC'
)->fetchAll(PDO::FETCH_ASSOC);

// Collect a small sample for inspection.
$samples = [];

foreach ([
    'route',
    'calls',
    'service_binding',
    'relation_hasMany',
] as $kind) {
    $stmt = $pdo->prepare(
        'SELECT src_fqn, dst_fqn, file, line, metadata
         FROM edges
         WHERE kind = ?
         ORDER BY file, line
         LIMIT 5'
    );

    $stmt->execute([$kind]);

    $samples[$kind] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Summarize the indexing results.
$result = [
    'repository' => $bookstack,
    'stats' => $graph->stats(),
    'files_scanned' => $indexStats->getFilesScanned(),
    'files_failed' => $indexStats->getFilesFailed(),
    'errors' => array_slice($indexStats->getErrors(), 0, 10),
    'edge_kinds' => $kinds,
    'sample_edges' => $samples,
];

echo json_encode(
    $result,
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_SLASHES |
    JSON_THROW_ON_ERROR
), PHP_EOL;

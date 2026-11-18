<?php
declare(strict_types=1);

require __DIR__ . '/../runtime-labs/mapin/vendor/autoload.php';

use Mapin\BuildRunner;
use Mapin\Extract\Contracts\ExtractionContext;
use Mapin\Store\SqliteStore;

$bookstack = realpath(__DIR__ . '/../../BookStack');

if ($bookstack === false || !is_dir($bookstack . '/app')) {
    fwrite(STDERR, "BookStack repository not found.\n");
    exit(1);
}

// Keep all generated data outside BookStack.
$database = __DIR__
    . '/../evidence/test-results/mapin-bookstack-static.sqlite';

// Run Mapin without booting Laravel.
$runner = new BuildRunner();

$report = $runner->run(
    root: $bookstack,
    storagePath: $database,
    projectPaths: ['app', 'routes'],
    vendorPaths: [],
    exclude: [],
    full: true,
    ctx: new ExtractionContext($bookstack, booted: false),
);

$store = new SqliteStore($database);
$pdo = $store->pdo();

// Count the actual stored records.
$counts = [];

foreach (['files', 'nodes', 'edges', 'unresolved'] as $table) {
    $counts[$table] = (int) $pdo->query(
        "SELECT COUNT(*) FROM {$table}"
    )->fetchColumn();
}

// Count extracted relationship categories.
$edgeTypes = $pdo->query(
    'SELECT type, COUNT(*) AS total
     FROM edges
     GROUP BY type
     ORDER BY total DESC'
)->fetchAll(PDO::FETCH_ASSOC);

// Count unresolved reference categories.
$unresolvedTypes = $pdo->query(
    'SELECT kind, COUNT(*) AS total
     FROM unresolved
     GROUP BY kind
     ORDER BY total DESC'
)->fetchAll(PDO::FETCH_ASSOC);

// Collect examples of FYP-relevant relationships.
$samples = [];

$query = $pdo->prepare(
    'SELECT
         e.type,
         source.key AS source,
         target.key AS destination,
         f.path AS file,
         e.line,
         e.resolution,
         e.confidence
     FROM edges e
     JOIN nodes source ON source.id = e.from_id
     JOIN nodes target ON target.id = e.to_id
     LEFT JOIN files f ON f.id = e.file_id
     WHERE e.type = ?
     ORDER BY f.path, e.line
     LIMIT 5'
);

foreach ([
    'calls',
    'injects',
    'renders',
    'relates',
    'routes_to',
] as $type) {
    $query->execute([$type]);
    $samples[$type] = $query->fetchAll(PDO::FETCH_ASSOC);
}

$result = [
    'repository' => $bookstack,
    'mode' => 'static_no_boot',
    'scan_paths' => ['app', 'routes'],
    'vendor_indexing' => false,
    'build_report' => $report->toArray(),
    'stored_counts' => $counts,
    'edge_types' => $edgeTypes,
    'unresolved_types' => $unresolvedTypes,
    'sample_edges' => $samples,
];

echo json_encode(
    $result,
    JSON_PRETTY_PRINT
        | JSON_UNESCAPED_SLASHES
        | JSON_THROW_ON_ERROR
), PHP_EOL;

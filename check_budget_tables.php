<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si las tablas existen
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];
$results = [];

foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    $results[$table] = $exists ? 'Existe' : 'No existe';
    
    if ($exists) {
        // Mostrar columnas si la tabla existe
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        $results[$table . '_columns'] = $columns;
    }
}

// Verificar registros en la tabla migrations
$migrations = DB::table('migrations')
    ->whereIn('migration', [
        '2025_08_19_153313_create_budget_controls_table',
        '2025_08_19_153318_create_budget_expenses_table',
        '2025_08_19_153326_create_budget_alerts_table'
    ])
    ->get();

echo "Estado de las tablas de presupuesto:\n";
foreach ($results as $key => $value) {
    if (strpos($key, '_columns') === false) {
        echo "- {$key}: {$value}\n";
    }
}

echo "\nRegistros en la tabla migrations:\n";
foreach ($migrations as $migration) {
    echo "- {$migration->migration} (Batch: {$migration->batch})\n";
}

if (count($migrations) < 3) {
    echo "\nFaltan registros de migración. Vamos a registrarlos manualmente.\n";
}

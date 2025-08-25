<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si las tablas existen
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];
$allTablesExist = true;

echo "Estado de las tablas de presupuesto:\n";
foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    echo "- {$table}: " . ($exists ? "EXISTE" : "NO EXISTE") . "\n";
    if (!$exists) {
        $allTablesExist = false;
    }
}

if (!$allTablesExist) {
    echo "\nNo todas las tablas existen. Por favor, ejecute el script fix_budget_tables.php primero.\n";
    exit(1);
}

// Verificar claves foráneas
echo "\nClaves foráneas en las tablas de presupuesto:\n";
foreach ($tables as $table) {
    echo "- {$table}:\n";
    
    $foreignKeys = DB::select("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_SCHEMA = DATABASE() AND
            TABLE_NAME = '{$table}' AND
            REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (count($foreignKeys) > 0) {
        foreach ($foreignKeys as $fk) {
            echo "  * {$fk->CONSTRAINT_NAME}: {$table}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    } else {
        echo "  * No se encontraron claves foráneas.\n";
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

echo "\nMigraciones registradas:\n";
foreach ($migrations as $migration) {
    echo "- {$migration->migration} (Batch: {$migration->batch})\n";
}

echo "\nVerificación completada.\n";

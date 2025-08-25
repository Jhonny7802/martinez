<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Función para verificar si una tabla existe
function tableExists($table) {
    return Schema::hasTable($table);
}

// Función para verificar las claves foráneas de una tabla
function checkForeignKeys($table) {
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
    
    return $foreignKeys;
}

// Verificar tablas de presupuesto
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];
echo "VERIFICACIÓN DE TABLAS DE PRESUPUESTO\n";
echo "===================================\n\n";

foreach ($tables as $table) {
    echo "TABLA: {$table}\n";
    echo "------------------------\n";
    
    // Verificar si la tabla existe
    $exists = tableExists($table);
    echo "Existe: " . ($exists ? "SÍ" : "NO") . "\n";
    
    if ($exists) {
        // Mostrar columnas
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        echo "Columnas: " . count($columns) . "\n";
        
        // Mostrar claves foráneas
        $foreignKeys = checkForeignKeys($table);
        echo "Claves foráneas: " . count($foreignKeys) . "\n";
        
        if (count($foreignKeys) > 0) {
            echo "Detalle de claves foráneas:\n";
            foreach ($foreignKeys as $fk) {
                echo "- {$fk->CONSTRAINT_NAME}: {$table}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
            }
        } else {
            echo "No se encontraron claves foráneas.\n";
        }
        
        // Contar registros
        $count = DB::table($table)->count();
        echo "Registros: {$count}\n";
    }
    
    echo "\n";
}

// Verificar registros en la tabla migrations
$migrations = DB::table('migrations')
    ->whereIn('migration', [
        '2025_08_19_153313_create_budget_controls_table',
        '2025_08_19_153318_create_budget_expenses_table',
        '2025_08_19_153326_create_budget_alerts_table'
    ])
    ->get();

echo "MIGRACIONES REGISTRADAS\n";
echo "======================\n\n";

if (count($migrations) > 0) {
    foreach ($migrations as $migration) {
        echo "- {$migration->migration} (Batch: {$migration->batch})\n";
    }
} else {
    echo "No se encontraron migraciones registradas para las tablas de presupuesto.\n";
}

echo "\nVerificación completada.\n";

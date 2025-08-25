<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar claves foráneas en las tablas de presupuesto
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];

foreach ($tables as $table) {
    echo "Claves foráneas en la tabla {$table}:\n";
    
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
            echo "- {$fk->CONSTRAINT_NAME}: {$table}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    } else {
        echo "- No se encontraron claves foráneas.\n";
    }
    
    echo "\n";
}

// Verificar si las tablas existen y tienen la estructura correcta
foreach ($tables as $table) {
    echo "Estructura de la tabla {$table}:\n";
    
    if (DB::statement("SHOW TABLES LIKE '{$table}'")) {
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        echo "- Columnas: " . count($columns) . "\n";
        
        // Contar registros
        $count = DB::table($table)->count();
        echo "- Registros: {$count}\n";
    } else {
        echo "- La tabla no existe.\n";
    }
    
    echo "\n";
}

echo "Verificación completada.\n";

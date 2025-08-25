<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Tablas a verificar
$tables = ['projects', 'users', 'expense_categories', 'budget_controls', 'budget_expenses', 'budget_alerts'];

foreach ($tables as $table) {
    echo "Tabla: {$table}\n";
    
    // Verificar si la tabla existe
    $exists = DB::select("SHOW TABLES LIKE '{$table}'");
    if (empty($exists)) {
        echo "  - No existe\n\n";
        continue;
    }
    
    // Mostrar columnas
    $columns = DB::select("SHOW COLUMNS FROM {$table}");
    echo "  - Columnas:\n";
    foreach ($columns as $column) {
        echo "    * {$column->Field}: {$column->Type} (Key: {$column->Key})\n";
    }
    
    // Contar registros
    $count = DB::table($table)->count();
    echo "  - Registros: {$count}\n\n";
}

echo "Verificación completada.\n";

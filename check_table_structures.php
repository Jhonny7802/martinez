<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Tablas a verificar
$tables = ['projects', 'users', 'expense_categories', 'budget_controls', 'budget_expenses', 'budget_alerts'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Tabla {$table} existe\n";
        echo "Estructura de la tabla {$table}:\n";
        
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        foreach ($columns as $column) {
            echo "- {$column->Field}: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
        }
        
        echo "\n";
    } else {
        echo "Tabla {$table} NO existe\n\n";
    }
}

// Verificar el motor de base de datos y charset
$tableStatus = DB::select("SHOW TABLE STATUS");
echo "Información de motor y charset de las tablas:\n";
foreach ($tableStatus as $status) {
    echo "- {$status->Name}: Engine={$status->Engine}, Charset={$status->Collation}\n";
}

echo "\nVerificación completada.\n";

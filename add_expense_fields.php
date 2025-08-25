<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Verificar si los campos ya existen
    $columns = DB::select("SHOW COLUMNS FROM projects");
    $existingColumns = [];
    
    foreach ($columns as $column) {
        $existingColumns[] = $column->Field;
    }
    
    echo "Columnas existentes en la tabla projects: " . implode(", ", $existingColumns) . "\n\n";
    
    // Agregar los campos que no existen
    $fieldsToAdd = [
        'last_expense_amount' => "ALTER TABLE projects ADD COLUMN last_expense_amount DECIMAL(15,2) NULL",
        'last_expense_date' => "ALTER TABLE projects ADD COLUMN last_expense_date DATE NULL",
        'last_expense_description' => "ALTER TABLE projects ADD COLUMN last_expense_description VARCHAR(255) NULL",
        'total_expenses' => "ALTER TABLE projects ADD COLUMN total_expenses DECIMAL(15,2) DEFAULT 0",
        'last_receipt_path' => "ALTER TABLE projects ADD COLUMN last_receipt_path VARCHAR(255) NULL"
    ];
    
    foreach ($fieldsToAdd as $field => $sql) {
        if (!in_array($field, $existingColumns)) {
            try {
                DB::statement($sql);
                echo "Campo {$field} agregado correctamente.\n";
            } catch (\Exception $e) {
                echo "Error al agregar el campo {$field}: " . $e->getMessage() . "\n";
            }
        } else {
            echo "El campo {$field} ya existe en la tabla.\n";
        }
    }
    
    echo "\nProceso completado.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

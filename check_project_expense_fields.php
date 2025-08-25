<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Verificar si la tabla projects existe
if (Schema::hasTable('projects')) {
    echo "La tabla projects existe.\n\n";
    
    // Verificar si existen los campos
    $hasLastExpenseAmount = Schema::hasColumn('projects', 'last_expense_amount');
    $hasLastExpenseDate = Schema::hasColumn('projects', 'last_expense_date');
    $hasLastExpenseDescription = Schema::hasColumn('projects', 'last_expense_description');
    $hasTotalExpenses = Schema::hasColumn('projects', 'total_expenses');
    $hasLastReceiptPath = Schema::hasColumn('projects', 'last_receipt_path');
    
    echo "Estado de los campos:\n";
    echo "- last_expense_amount: " . ($hasLastExpenseAmount ? "Existe" : "No existe") . "\n";
    echo "- last_expense_date: " . ($hasLastExpenseDate ? "Existe" : "No existe") . "\n";
    echo "- last_expense_description: " . ($hasLastExpenseDescription ? "Existe" : "No existe") . "\n";
    echo "- total_expenses: " . ($hasTotalExpenses ? "Existe" : "No existe") . "\n";
    echo "- last_receipt_path: " . ($hasLastReceiptPath ? "Existe" : "No existe") . "\n\n";
    
    // Mostrar las columnas de la tabla
    echo "Columnas de la tabla projects:\n";
    $columns = DB::select("SHOW COLUMNS FROM projects");
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type} (Nullable: {$column->Null})\n";
    }
} else {
    echo "ERROR: La tabla projects no existe.\n";
}

echo "\nVerificación completada.\n";

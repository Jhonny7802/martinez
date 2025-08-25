<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Verificar si la tabla projects existe
if (Schema::hasTable('projects')) {
    echo "La tabla projects existe. Agregando campos para gastos...\n";
    
    // Verificar si ya existen los campos
    $hasLastExpenseAmount = Schema::hasColumn('projects', 'last_expense_amount');
    $hasLastExpenseDate = Schema::hasColumn('projects', 'last_expense_date');
    $hasLastExpenseDescription = Schema::hasColumn('projects', 'last_expense_description');
    $hasTotalExpenses = Schema::hasColumn('projects', 'total_expenses');
    $hasLastReceiptPath = Schema::hasColumn('projects', 'last_receipt_path');
    
    if (!$hasLastExpenseAmount || !$hasLastExpenseDate || !$hasLastExpenseDescription || !$hasTotalExpenses || !$hasLastReceiptPath) {
        Schema::table('projects', function (Blueprint $table) use ($hasLastExpenseAmount, $hasLastExpenseDate, $hasLastExpenseDescription, $hasTotalExpenses, $hasLastReceiptPath) {
            if (!$hasLastExpenseAmount) {
                $table->decimal('last_expense_amount', 15, 2)->nullable();
                echo "- Campo last_expense_amount agregado.\n";
            }
            
            if (!$hasLastExpenseDate) {
                $table->date('last_expense_date')->nullable();
                echo "- Campo last_expense_date agregado.\n";
            }
            
            if (!$hasLastExpenseDescription) {
                $table->string('last_expense_description')->nullable();
                echo "- Campo last_expense_description agregado.\n";
            }
            
            if (!$hasTotalExpenses) {
                $table->decimal('total_expenses', 15, 2)->default(0);
                echo "- Campo total_expenses agregado.\n";
            }
            
            if (!$hasLastReceiptPath) {
                $table->string('last_receipt_path')->nullable();
                echo "- Campo last_receipt_path agregado.\n";
            }
        });
        
        echo "\nCampos agregados correctamente a la tabla projects.\n";
    } else {
        echo "Todos los campos necesarios ya existen en la tabla projects.\n";
    }
} else {
    echo "ERROR: La tabla projects no existe.\n";
}

echo "\nProceso completado.\n";

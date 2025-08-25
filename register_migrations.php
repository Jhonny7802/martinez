<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si las tablas existen
$budgetControlsExists = Schema::hasTable('budget_controls');
$budgetExpensesExists = Schema::hasTable('budget_expenses');
$budgetAlertsExists = Schema::hasTable('budget_alerts');

echo "Estado actual de las tablas:\n";
echo "budget_controls: " . ($budgetControlsExists ? "Existe" : "No existe") . "\n";
echo "budget_expenses: " . ($budgetExpensesExists ? "Existe" : "No existe") . "\n";
echo "budget_alerts: " . ($budgetAlertsExists ? "Existe" : "No existe") . "\n\n";

// Si las tablas no existen, crear las tablas
if (!$budgetControlsExists) {
    echo "Creando tabla budget_controls...\n";
    Schema::create('budget_controls', function ($table) {
        $table->id();
        $table->unsignedBigInteger('project_id');
        $table->string('name');
        $table->decimal('initial_budget', 15, 2)->default(0);
        $table->decimal('current_budget', 15, 2)->default(0);
        $table->decimal('spent_amount', 15, 2)->default(0);
        $table->decimal('remaining_amount', 15, 2)->default(0);
        $table->decimal('budget_percent', 8, 2)->default(0);
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->enum('status', ['active', 'completed', 'on_hold', 'cancelled'])->default('active');
        $table->text('description')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
    
    // Agregar foreign key
    Schema::table('budget_controls', function ($table) {
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    });
    
    echo "Tabla budget_controls creada.\n";
}

if (!$budgetExpensesExists) {
    echo "Creando tabla budget_expenses...\n";
    Schema::create('budget_expenses', function ($table) {
        $table->id();
        $table->unsignedBigInteger('budget_control_id');
        $table->unsignedBigInteger('project_id');
        $table->string('expense_name');
        $table->decimal('amount', 15, 2);
        $table->date('expense_date');
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
        $table->unsignedBigInteger('category_id')->nullable();
        $table->string('receipt_number')->nullable();
        $table->string('invoice_number')->nullable();
        $table->string('receipt_path')->nullable();
        $table->text('notes')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamps();
        
        $table->foreign('budget_control_id')->references('id')->on('budget_controls')->onDelete('cascade');
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('set null');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
    });
    
    echo "Tabla budget_expenses creada.\n";
}

if (!$budgetAlertsExists) {
    echo "Creando tabla budget_alerts...\n";
    Schema::create('budget_alerts', function ($table) {
        $table->id();
        $table->unsignedBigInteger('budget_control_id');
        $table->unsignedBigInteger('project_id');
        $table->string('title');
        $table->text('description');
        $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
        $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
        $table->decimal('threshold_percent', 8, 2)->nullable();
        $table->decimal('current_percent', 8, 2)->nullable();
        $table->decimal('budget_amount', 15, 2)->nullable();
        $table->decimal('spent_amount', 15, 2)->nullable();
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('acknowledged_by')->nullable();
        $table->timestamp('acknowledged_at')->nullable();
        $table->unsignedBigInteger('resolved_by')->nullable();
        $table->timestamp('resolved_at')->nullable();
        $table->timestamps();
        
        $table->foreign('budget_control_id')->references('id')->on('budget_controls')->onDelete('cascade');
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('acknowledged_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
    });
    
    echo "Tabla budget_alerts creada.\n";
}

// Registrar las migraciones en la tabla migrations
$migrations = [
    '2025_08_19_153313_create_budget_controls_table',
    '2025_08_19_153318_create_budget_expenses_table',
    '2025_08_19_153326_create_budget_alerts_table'
];

$batch = DB::table('migrations')->max('batch') + 1;

foreach ($migrations as $migration) {
    if (!DB::table('migrations')->where('migration', $migration)->exists()) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);
        echo "Migración {$migration} registrada en la tabla migrations.\n";
    } else {
        echo "Migración {$migration} ya estaba registrada.\n";
    }
}

echo "\nProceso completado.\n";

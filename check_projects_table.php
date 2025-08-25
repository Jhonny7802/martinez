<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si la tabla projects existe
$projectsExists = Schema::hasTable('projects');
echo "Tabla projects: " . ($projectsExists ? "Existe" : "No existe") . "\n";

if ($projectsExists) {
    // Obtener la estructura de la tabla projects
    $columns = DB::select('SHOW COLUMNS FROM projects');
    echo "Estructura de la tabla projects:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
    }
}

// Verificar si las tablas de presupuesto ya existen
$budgetControlsExists = Schema::hasTable('budget_controls');
$budgetExpensesExists = Schema::hasTable('budget_expenses');
$budgetAlertsExists = Schema::hasTable('budget_alerts');

echo "\nEstado actual de las tablas de presupuesto:\n";
echo "budget_controls: " . ($budgetControlsExists ? "Existe" : "No existe") . "\n";
echo "budget_expenses: " . ($budgetExpensesExists ? "Existe" : "No existe") . "\n";
echo "budget_alerts: " . ($budgetAlertsExists ? "Existe" : "No existe") . "\n";

if ($budgetControlsExists) {
    // Si la tabla budget_controls existe, eliminarla
    echo "\nEliminando tablas existentes para recrearlas...\n";
    
    if ($budgetAlertsExists) {
        Schema::dropIfExists('budget_alerts');
        echo "Tabla budget_alerts eliminada.\n";
    }
    
    if ($budgetExpensesExists) {
        Schema::dropIfExists('budget_expenses');
        echo "Tabla budget_expenses eliminada.\n";
    }
    
    if ($budgetControlsExists) {
        Schema::dropIfExists('budget_controls');
        echo "Tabla budget_controls eliminada.\n";
    }
}

echo "\nProceso completado. Ahora puede ejecutar las migraciones nuevamente.\n";

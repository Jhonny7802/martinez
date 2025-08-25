<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar la estructura de la tabla projects
echo "Verificando estructura de la tabla projects...\n";
$projectsColumns = DB::select('SHOW COLUMNS FROM projects');
$projectIdColumn = null;

foreach ($projectsColumns as $column) {
    if ($column->Field === 'id') {
        $projectIdColumn = $column;
        echo "Columna 'id' en projects: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
    }
}

if (!$projectIdColumn) {
    echo "ERROR: No se encontró la columna 'id' en la tabla projects.\n";
    exit(1);
}

// Verificar la estructura de la tabla users
echo "\nVerificando estructura de la tabla users...\n";
$usersColumns = DB::select('SHOW COLUMNS FROM users');
$userIdColumn = null;

foreach ($usersColumns as $column) {
    if ($column->Field === 'id') {
        $userIdColumn = $column;
        echo "Columna 'id' en users: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
    }
}

if (!$userIdColumn) {
    echo "ERROR: No se encontró la columna 'id' en la tabla users.\n";
    exit(1);
}

// Verificar la estructura de la tabla expense_categories
echo "\nVerificando estructura de la tabla expense_categories...\n";
$expenseCategoriesColumns = DB::select('SHOW COLUMNS FROM expense_categories');
$expenseCategoryIdColumn = null;

foreach ($expenseCategoriesColumns as $column) {
    if ($column->Field === 'id') {
        $expenseCategoryIdColumn = $column;
        echo "Columna 'id' en expense_categories: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
    }
}

if (!$expenseCategoryIdColumn) {
    echo "ERROR: No se encontró la columna 'id' en la tabla expense_categories.\n";
    exit(1);
}

// Desactivar temporalmente las restricciones de clave foránea
echo "\nDesactivando restricciones de clave foránea...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');

try {
    // Agregar claves foráneas a budget_controls
    echo "\nAgregando claves foráneas a budget_controls...\n";
    DB::statement("ALTER TABLE `budget_controls` ADD CONSTRAINT `budget_controls_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_controls.project_id -> projects.id agregada.\n";
    
    // Agregar claves foráneas a budget_expenses
    echo "\nAgregando claves foráneas a budget_expenses...\n";
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_expenses.budget_control_id -> budget_controls.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_expenses.project_id -> projects.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL");
    echo "✓ Foreign key budget_expenses.category_id -> expense_categories.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_expenses.created_by -> users.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    echo "✓ Foreign key budget_expenses.approved_by -> users.id agregada.\n";
    
    // Agregar claves foráneas a budget_alerts
    echo "\nAgregando claves foráneas a budget_alerts...\n";
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_alerts.budget_control_id -> budget_controls.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_alerts.project_id -> projects.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
    echo "✓ Foreign key budget_alerts.created_by -> users.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    echo "✓ Foreign key budget_alerts.acknowledged_by -> users.id agregada.\n";
    
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    echo "✓ Foreign key budget_alerts.resolved_by -> users.id agregada.\n";
    
    echo "\nTodas las claves foráneas han sido agregadas correctamente.\n";
} catch (Exception $e) {
    echo "\nERROR al agregar claves foráneas: " . $e->getMessage() . "\n";
}

// Reactivar las restricciones de clave foránea
echo "\nReactivando restricciones de clave foránea...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\nProceso completado.\n";

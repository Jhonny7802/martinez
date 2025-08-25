<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Función para verificar si una tabla existe
function tableExists($table) {
    try {
        return Schema::hasTable($table);
    } catch (Exception $e) {
        echo "Error al verificar tabla {$table}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Verificar tablas existentes
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];
echo "Verificando tablas existentes:\n";

foreach ($tables as $table) {
    $exists = tableExists($table);
    echo "- {$table}: " . ($exists ? "EXISTE" : "NO EXISTE") . "\n";
}

// Desactivar verificación de claves foráneas
echo "\nDesactivando verificación de claves foráneas...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Eliminar tablas existentes
echo "\nEliminando tablas existentes...\n";
foreach ($tables as $table) {
    if (tableExists($table)) {
        try {
            DB::statement("DROP TABLE IF EXISTS `{$table}`");
            echo "- Tabla {$table} eliminada.\n";
        } catch (Exception $e) {
            echo "- Error al eliminar tabla {$table}: " . $e->getMessage() . "\n";
        }
    }
}

// Crear tablas nuevamente
echo "\nCreando tablas nuevamente...\n";

// Crear tabla budget_controls
try {
    DB::statement("CREATE TABLE `budget_controls` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `project_id` bigint(20) UNSIGNED DEFAULT NULL,
        `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `initial_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
        `current_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
        `spent_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
        `remaining_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
        `budget_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
        `start_date` date DEFAULT NULL,
        `end_date` date DEFAULT NULL,
        `status` enum('active','completed','on_hold','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
        `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `budget_controls_project_id_index` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "- Tabla budget_controls creada.\n";
} catch (Exception $e) {
    echo "- Error al crear tabla budget_controls: " . $e->getMessage() . "\n";
}

// Crear tabla budget_expenses
try {
    DB::statement("CREATE TABLE `budget_expenses` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `budget_control_id` bigint(20) UNSIGNED NOT NULL,
        `project_id` bigint(20) UNSIGNED DEFAULT NULL,
        `expense_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `amount` decimal(15,2) NOT NULL,
        `expense_date` date NOT NULL,
        `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
        `payment_status` enum('unpaid','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
        `category_id` bigint(20) UNSIGNED DEFAULT NULL,
        `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_by` bigint(20) UNSIGNED NOT NULL,
        `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
        `approved_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `budget_expenses_budget_control_id_index` (`budget_control_id`),
        KEY `budget_expenses_project_id_index` (`project_id`),
        KEY `budget_expenses_category_id_index` (`category_id`),
        KEY `budget_expenses_created_by_index` (`created_by`),
        KEY `budget_expenses_approved_by_index` (`approved_by`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "- Tabla budget_expenses creada.\n";
} catch (Exception $e) {
    echo "- Error al crear tabla budget_expenses: " . $e->getMessage() . "\n";
}

// Crear tabla budget_alerts
try {
    DB::statement("CREATE TABLE `budget_alerts` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `budget_control_id` bigint(20) UNSIGNED NOT NULL,
        `project_id` bigint(20) UNSIGNED DEFAULT NULL,
        `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `severity` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
        `status` enum('active','acknowledged','resolved','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
        `threshold_percent` decimal(8,2) DEFAULT NULL,
        `current_percent` decimal(8,2) DEFAULT NULL,
        `budget_amount` decimal(15,2) DEFAULT NULL,
        `spent_amount` decimal(15,2) DEFAULT NULL,
        `created_by` bigint(20) UNSIGNED NOT NULL,
        `acknowledged_by` bigint(20) UNSIGNED DEFAULT NULL,
        `acknowledged_at` timestamp NULL DEFAULT NULL,
        `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
        `resolved_at` timestamp NULL DEFAULT NULL,
        `resolution_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `budget_alerts_budget_control_id_index` (`budget_control_id`),
        KEY `budget_alerts_project_id_index` (`project_id`),
        KEY `budget_alerts_created_by_index` (`created_by`),
        KEY `budget_alerts_acknowledged_by_index` (`acknowledged_by`),
        KEY `budget_alerts_resolved_by_index` (`resolved_by`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "- Tabla budget_alerts creada.\n";
} catch (Exception $e) {
    echo "- Error al crear tabla budget_alerts: " . $e->getMessage() . "\n";
}

// Intentar agregar las claves foráneas
echo "\nAgregando claves foráneas...\n";

// Función para agregar una clave foránea
function addForeignKey($table, $column, $refTable, $refColumn, $onDelete = 'CASCADE') {
    try {
        $constraintName = "{$table}_{$column}_foreign";
        DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}` (`{$refColumn}`) ON DELETE {$onDelete}");
        echo "- Clave foránea agregada: {$table}.{$column} -> {$refTable}.{$refColumn}\n";
        return true;
    } catch (Exception $e) {
        echo "- Error al agregar clave foránea {$table}.{$column}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Agregar claves foráneas para budget_controls
addForeignKey('budget_controls', 'project_id', 'projects', 'id');

// Agregar claves foráneas para budget_expenses
addForeignKey('budget_expenses', 'budget_control_id', 'budget_controls', 'id');
addForeignKey('budget_expenses', 'project_id', 'projects', 'id');
addForeignKey('budget_expenses', 'category_id', 'expense_categories', 'id', 'SET NULL');
addForeignKey('budget_expenses', 'created_by', 'users', 'id');
addForeignKey('budget_expenses', 'approved_by', 'users', 'id', 'SET NULL');

// Agregar claves foráneas para budget_alerts
addForeignKey('budget_alerts', 'budget_control_id', 'budget_controls', 'id');
addForeignKey('budget_alerts', 'project_id', 'projects', 'id');
addForeignKey('budget_alerts', 'created_by', 'users', 'id');
addForeignKey('budget_alerts', 'acknowledged_by', 'users', 'id', 'SET NULL');
addForeignKey('budget_alerts', 'resolved_by', 'users', 'id', 'SET NULL');

// Reactivar verificación de claves foráneas
echo "\nReactivando verificación de claves foráneas...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Registrar las migraciones en la tabla migrations
$migrations = [
    '2025_08_19_153313_create_budget_controls_table',
    '2025_08_19_153318_create_budget_expenses_table',
    '2025_08_19_153326_create_budget_alerts_table'
];

$batch = DB::table('migrations')->max('batch') + 1;

foreach ($migrations as $migration) {
    // Eliminar el registro si ya existe
    DB::table('migrations')->where('migration', $migration)->delete();
    
    // Insertar el registro
    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => $batch
    ]);
    echo "Migración {$migration} registrada en la tabla migrations.\n";
}

echo "\nProceso completado.\n";

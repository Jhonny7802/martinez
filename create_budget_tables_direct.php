<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Desactivar temporalmente las restricciones de clave foránea
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Eliminar las tablas si existen
DB::statement('DROP TABLE IF EXISTS budget_alerts');
DB::statement('DROP TABLE IF EXISTS budget_expenses');
DB::statement('DROP TABLE IF EXISTS budget_controls');

// Reactivar las restricciones de clave foránea
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Crear tabla budget_controls
$sql_budget_controls = "CREATE TABLE `budget_controls` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id` bigint(20) UNSIGNED NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `initial_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
    `current_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
    `spent_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
    `remaining_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
    `budget_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
    `start_date` date NULL DEFAULT NULL,
    `end_date` date NULL DEFAULT NULL,
    `status` enum('active','completed','on_hold','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
    `description` text COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `notes` text COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `budget_controls_project_id_foreign` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Crear tabla budget_expenses
$sql_budget_expenses = "CREATE TABLE `budget_expenses` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `budget_control_id` bigint(20) UNSIGNED NOT NULL,
    `project_id` bigint(20) UNSIGNED NULL,
    `expense_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `amount` decimal(15,2) NOT NULL,
    `expense_date` date NOT NULL,
    `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
    `payment_status` enum('unpaid','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
    `category_id` bigint(20) UNSIGNED NULL,
    `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `notes` text COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `created_by` bigint(20) UNSIGNED NOT NULL,
    `approved_by` bigint(20) UNSIGNED NULL,
    `approved_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `budget_expenses_budget_control_id_foreign` (`budget_control_id`),
    KEY `budget_expenses_project_id_foreign` (`project_id`),
    KEY `budget_expenses_category_id_foreign` (`category_id`),
    KEY `budget_expenses_created_by_foreign` (`created_by`),
    KEY `budget_expenses_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Crear tabla budget_alerts
$sql_budget_alerts = "CREATE TABLE `budget_alerts` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `budget_control_id` bigint(20) UNSIGNED NOT NULL,
    `project_id` bigint(20) UNSIGNED NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `severity` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
    `status` enum('active','acknowledged','resolved','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
    `threshold_percent` decimal(8,2) NULL DEFAULT NULL,
    `current_percent` decimal(8,2) NULL DEFAULT NULL,
    `budget_amount` decimal(15,2) NULL DEFAULT NULL,
    `spent_amount` decimal(15,2) NULL DEFAULT NULL,
    `created_by` bigint(20) UNSIGNED NOT NULL,
    `acknowledged_by` bigint(20) UNSIGNED NULL,
    `acknowledged_at` timestamp NULL DEFAULT NULL,
    `resolved_by` bigint(20) UNSIGNED NULL,
    `resolved_at` timestamp NULL DEFAULT NULL,
    `resolution_notes` text COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `budget_alerts_budget_control_id_foreign` (`budget_control_id`),
    KEY `budget_alerts_project_id_foreign` (`project_id`),
    KEY `budget_alerts_created_by_foreign` (`created_by`),
    KEY `budget_alerts_acknowledged_by_foreign` (`acknowledged_by`),
    KEY `budget_alerts_resolved_by_foreign` (`resolved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Ejecutar las consultas SQL para crear las tablas
try {
    DB::statement($sql_budget_controls);
    echo "Tabla budget_controls creada correctamente.\n";
    
    DB::statement($sql_budget_expenses);
    echo "Tabla budget_expenses creada correctamente.\n";
    
    DB::statement($sql_budget_alerts);
    echo "Tabla budget_alerts creada correctamente.\n";
    
    // Agregar las foreign keys por separado
    DB::statement("ALTER TABLE `budget_controls` ADD CONSTRAINT `budget_controls_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    echo "Foreign key para budget_controls agregada.\n";
    
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL");
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_expenses` ADD CONSTRAINT `budget_expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    echo "Foreign keys para budget_expenses agregadas.\n";
    
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    DB::statement("ALTER TABLE `budget_alerts` ADD CONSTRAINT `budget_alerts_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
    echo "Foreign keys para budget_alerts agregadas.\n";
} catch (Exception $e) {
    echo "Error al crear las tablas: " . $e->getMessage() . "\n";
}

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

echo "\nProceso completado. Las tablas de presupuesto han sido creadas y las migraciones registradas correctamente.\n";

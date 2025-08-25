-- Desactivar temporalmente las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS=0;

-- Eliminar las tablas si existen
DROP TABLE IF EXISTS budget_alerts;
DROP TABLE IF EXISTS budget_expenses;
DROP TABLE IF EXISTS budget_controls;

-- Reactivar las restricciones de clave foránea
SET FOREIGN_KEY_CHECKS=1;

-- Crear tabla budget_controls
CREATE TABLE `budget_controls` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `total_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_spent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remaining_budget` decimal(15,2) NOT NULL DEFAULT '0.00',
  `budget_status` tinyint(4) NOT NULL DEFAULT '1',
  `last_updated` timestamp NULL DEFAULT NULL,
  `alert_threshold` decimal(5,2) NOT NULL DEFAULT '80.00',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_controls_project_id_foreign` (`project_id`),
  CONSTRAINT `budget_controls_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla budget_expenses
CREATE TABLE `budget_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `budget_control_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `expense_date` date NOT NULL,
  `receipt_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_expenses_budget_control_id_foreign` (`budget_control_id`),
  KEY `budget_expenses_category_id_foreign` (`category_id`),
  KEY `budget_expenses_created_by_foreign` (`created_by`),
  CONSTRAINT `budget_expenses_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla budget_alerts
CREATE TABLE `budget_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `budget_control_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `alert_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` tinyint(4) NOT NULL DEFAULT '1',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `acknowledged_by` bigint(20) UNSIGNED DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_alerts_budget_control_id_foreign` (`budget_control_id`),
  KEY `budget_alerts_project_id_foreign` (`project_id`),
  KEY `budget_alerts_created_by_foreign` (`created_by`),
  KEY `budget_alerts_acknowledged_by_foreign` (`acknowledged_by`),
  CONSTRAINT `budget_alerts_budget_control_id_foreign` FOREIGN KEY (`budget_control_id`) REFERENCES `budget_controls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_alerts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_alerts_acknowledged_by_foreign` FOREIGN KEY (`acknowledged_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Registrar las migraciones en la tabla migrations
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2025_08_19_153313_create_budget_controls_table', (SELECT MAX(batch) + 1 FROM `migrations` AS m)),
('2025_08_19_153318_create_budget_expenses_table', (SELECT MAX(batch) + 1 FROM `migrations` AS m)),
('2025_08_19_153326_create_budget_alerts_table', (SELECT MAX(batch) + 1 FROM `migrations` AS m));

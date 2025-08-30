-- =====================================================
-- Martinez Construction Management System
-- Railway Database Setup Script
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- =====================================================
-- CORE SYSTEM TABLES
-- =====================================================

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- USER MANAGEMENT
-- =====================================================

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CUSTOMER MANAGEMENT
-- =====================================================

CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(3) DEFAULT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PROJECT MANAGEMENT
-- =====================================================

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `calculate_progress_through_tasks` tinyint(1) DEFAULT '0',
  `progress` varchar(255) DEFAULT NULL,
  `billing_type` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `estimated_hours` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `description` text,
  `send_email` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_customer_id_foreign` (`customer_id`),
  CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INVENTORY MANAGEMENT
-- =====================================================

CREATE TABLE `item_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `unit` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT '0.00',
  `cost_price` decimal(15,2) DEFAULT '0.00',
  `item_group_id` bigint unsigned DEFAULT NULL,
  `stock_quantity` int DEFAULT '0',
  `minimum_stock` int DEFAULT '0',
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_item_group_id_foreign` (`item_group_id`),
  CONSTRAINT `items_item_group_id_foreign` FOREIGN KEY (`item_group_id`) REFERENCES `item_groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CAI BILLING SYSTEM
-- =====================================================

CREATE TABLE `cai_billings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cai_number` varchar(255) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_rtn` varchar(255) DEFAULT NULL,
  `customer_address` text,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL,
  `issue_date` date NOT NULL,
  `status` enum('draft','issued','paid','cancelled') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cai_billings_cai_number_unique` (`cai_number`),
  UNIQUE KEY `cai_billings_invoice_number_unique` (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DESIGN MODULE
-- =====================================================

CREATE TABLE `design_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `dimensions` varchar(255) NOT NULL DEFAULT '1920x1080',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `design_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','in_progress','review','completed','cancelled') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_projects_customer_id_foreign` (`customer_id`),
  KEY `design_projects_template_id_foreign` (`template_id`),
  CONSTRAINT `design_projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_projects_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `design_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- CONFIGURATION
-- =====================================================

CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(50) DEFAULT 'string',
  `group` varchar(100) DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INITIAL DATA
-- =====================================================

INSERT INTO `users` (`name`, `email`, `status`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
('Administrator', 'admin@martinez-construction.com', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());

INSERT INTO `countries` (`name`, `code`, `phone_code`, `created_at`, `updated_at`) VALUES
('Honduras', 'HN', '+504', NOW(), NOW()),
('United States', 'US', '+1', NOW(), NOW());

INSERT INTO `departments` (`name`, `description`, `created_at`, `updated_at`) VALUES
('Construcción', 'Departamento de construcción y obras', NOW(), NOW()),
('Administración', 'Departamento administrativo', NOW(), NOW()),
('Diseño', 'Departamento de diseño gráfico', NOW(), NOW());

INSERT INTO `item_groups` (`name`, `description`, `created_at`, `updated_at`) VALUES
('Cemento y Concreto', 'Materiales de cemento y concreto', NOW(), NOW()),
('Acero y Hierro', 'Materiales de acero y hierro', NOW(), NOW()),
('Herramientas', 'Herramientas de construcción', NOW(), NOW());

INSERT INTO `design_templates` (`name`, `description`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
('Plantilla Básica', 'Plantilla básica para diseños generales', 'general', 1, NOW(), NOW()),
('Logo Corporativo', 'Plantilla para diseño de logos', 'branding', 1, NOW(), NOW());

INSERT INTO `settings` (`key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
('company_name', 'Martinez Construction', 'string', 'company', NOW(), NOW()),
('company_email', 'info@martinez-construction.com', 'string', 'company', NOW(), NOW()),
('currency', 'HNL', 'string', 'general', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

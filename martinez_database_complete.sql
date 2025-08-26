-- Martinez Construction Management System - Complete Database
-- Generated: 2025-08-25
-- Database: martinez
-- Version: Laravel 9 Compatible

SET FOREIGN_KEY_CHECKS = 0;

-- Create database
CREATE DATABASE IF NOT EXISTS martinez CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE martinez;

-- Drop existing tables
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `items`;
-- Drop all tables in correct order to avoid foreign key issues
DROP TABLE IF EXISTS `budget_alerts`;
DROP TABLE IF EXISTS `progress_reports`;
DROP TABLE IF EXISTS `project_activities`;
DROP TABLE IF EXISTS `inventory_movements`;
DROP TABLE IF EXISTS `material_requisition_items`;
DROP TABLE IF EXISTS `material_requisitions`;
DROP TABLE IF EXISTS `internal_messages`;
DROP TABLE IF EXISTS `ticket_replies`;
DROP TABLE IF EXISTS `activity_log`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `sales_items_taxes`;
DROP TABLE IF EXISTS `sales_taxes`;
DROP TABLE IF EXISTS `sales_items`;
DROP TABLE IF EXISTS `invoice_payment_modes`;
DROP TABLE IF EXISTS `invoice_addresses`;
DROP TABLE IF EXISTS `estimate_addresses`;
DROP TABLE IF EXISTS `proposal_addresses`;
DROP TABLE IF EXISTS `credit_note_addresses`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `credit_notes`;
DROP TABLE IF EXISTS `estimates`;
DROP TABLE IF EXISTS `proposals`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `contracts`;
DROP TABLE IF EXISTS `leads`;
DROP TABLE IF EXISTS `goals`;
DROP TABLE IF EXISTS `goal_members`;
DROP TABLE IF EXISTS `goal_types`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `project_contacts`;
DROP TABLE IF EXISTS `project_members`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `reminders`;
DROP TABLE IF EXISTS `notes`;
DROP TABLE IF EXISTS `contact_email_notifications`;
DROP TABLE IF EXISTS `email_notifications`;
DROP TABLE IF EXISTS `email_templates`;
DROP TABLE IF EXISTS `user_departments`;
DROP TABLE IF EXISTS `media`;
DROP TABLE IF EXISTS `taggables`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `address`;
DROP TABLE IF EXISTS `customer_to_customer_groups`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `predefined_replies`;
DROP TABLE IF EXISTS `article_groups`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `customer_groups`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `languages`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `ticket_statuses`;
DROP TABLE IF EXISTS `ticket_priorities`;
DROP TABLE IF EXISTS `lead_sources`;
DROP TABLE IF EXISTS `lead_statuses`;
DROP TABLE IF EXISTS `tax_rates`;
DROP TABLE IF EXISTS `item_groups`;
DROP TABLE IF EXISTS `payment_modes`;
DROP TABLE IF EXISTS `expense_categories`;
DROP TABLE IF EXISTS `contract_types`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `password_resets`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `migrations`;

-- Create migrations table
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
);

-- Create users table
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
);

-- Create countries table
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(3) DEFAULT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create departments table
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create customers table
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
);

-- Create contacts table
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create projects table
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
  PRIMARY KEY (`id`)
);

-- Create item_groups table
CREATE TABLE `item_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create items table
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
  `maximum_stock` int DEFAULT NULL,
  `reorder_level` int DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create expense_categories table
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create payment_modes table
CREATE TABLE `payment_modes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create tax_rates table
CREATE TABLE `tax_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rate` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create invoices table
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('draft','sent','paid','overdue','cancelled') DEFAULT 'draft',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`)
);

-- Create estimates table
CREATE TABLE `estimates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `estimate_number` varchar(255) NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `estimate_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('draft','sent','accepted','declined','expired') DEFAULT 'draft',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estimates_estimate_number_unique` (`estimate_number`)
);

-- Create tasks table
CREATE TABLE `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `public` tinyint(1) DEFAULT '0',
  `billable` tinyint(1) DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `hourly_rate` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `description` text,
  `related_to` int DEFAULT NULL,
  `owner_type` varchar(255) DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `member_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create tickets table
CREATE TABLE `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','in_progress','closed','on_hold') DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `department_id` bigint unsigned DEFAULT NULL,
  `contact_id` bigint unsigned DEFAULT NULL,
  `assign_to` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create settings table
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(255) DEFAULT 'string',
  `group` varchar(255) DEFAULT 'general',
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
);

-- Create permissions table
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
);

-- Create roles table
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
);

-- Create role_has_permissions table
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`)
);

-- Create model_has_permissions table
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`)
);

-- Create model_has_roles table
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`)
);

-- Create internal_messages table
CREATE TABLE `internal_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `recipients` json NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `attachments` json DEFAULT NULL,
  `read_by` json DEFAULT NULL,
  `is_broadcast` tinyint(1) DEFAULT '0',
  `parent_message_id` bigint unsigned DEFAULT NULL,
  `message_type` enum('normal','reply','forward','broadcast') DEFAULT 'normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `internal_messages_sender_id_index` (`sender_id`),
  KEY `internal_messages_priority_index` (`priority`),
  KEY `internal_messages_is_broadcast_index` (`is_broadcast`),
  KEY `internal_messages_created_at_index` (`created_at`)
);

-- Create material_requisitions table
CREATE TABLE `material_requisitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `requisition_number` varchar(255) NOT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `requested_by` int unsigned NOT NULL,
  `approved_by` int unsigned DEFAULT NULL,
  `status` enum('pending','approved','rejected','delivered','cancelled') DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `required_date` date NOT NULL,
  `purpose` text,
  `notes` text,
  `total_cost` decimal(15,2) DEFAULT '0.00',
  `approved_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_requisitions_requisition_number_unique` (`requisition_number`),
  KEY `material_requisitions_project_id_index` (`project_id`)
);

-- Create material_requisition_items table
CREATE TABLE `material_requisition_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `requisition_id` bigint unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `quantity_requested` int NOT NULL,
  `quantity_approved` int DEFAULT NULL,
  `quantity_delivered` int DEFAULT '0',
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `total_cost` decimal(15,2) DEFAULT NULL,
  `specifications` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create inventory_movements table
CREATE TABLE `inventory_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `movement_type` enum('in','out','adjustment','transfer') NOT NULL,
  `quantity` int NOT NULL,
  `previous_stock` int NOT NULL,
  `new_stock` int NOT NULL,
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Insert basic data
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `status`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', NOW(), 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());

INSERT INTO `countries` (`id`, `name`, `code`, `phone_code`, `created_at`, `updated_at`) VALUES
(1, 'Honduras', 'HN', '+504', NOW(), NOW()),
(2, 'United States', 'US', '+1', NOW(), NOW()),
(3, 'Mexico', 'MX', '+52', NOW(), NOW());

INSERT INTO `settings` (`key`, `value`, `type`, `group`, `description`, `created_at`, `updated_at`) VALUES
('company_name', 'Martinez Construction', 'string', 'company', 'Company name', NOW(), NOW()),
('company_email', 'info@martinez-construction.com', 'string', 'company', 'Company email', NOW(), NOW()),
('company_phone', '+504-9999-9999', 'string', 'company', 'Company phone', NOW(), NOW()),
('currency', 'HNL', 'string', 'general', 'Default currency', NOW(), NOW()),
('timezone', 'America/Tegucigalpa', 'string', 'general', 'Default timezone', NOW(), NOW());

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Construcción', 'Departamento de construcción y obras', NOW(), NOW()),
(2, 'Administración', 'Departamento administrativo', NOW(), NOW()),
(3, 'Ventas', 'Departamento de ventas y proyectos', NOW(), NOW());

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Materiales', 'Gastos en materiales de construcción', NOW(), NOW()),
(2, 'Mano de Obra', 'Gastos en personal y contratistas', NOW(), NOW()),
(3, 'Equipos', 'Alquiler y compra de equipos', NOW(), NOW()),
(4, 'Transporte', 'Gastos de transporte y combustible', NOW(), NOW()),
(5, 'Administrativos', 'Gastos administrativos generales', NOW(), NOW());

INSERT INTO `item_groups` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cemento y Concreto', 'Materiales de cemento y concreto', NOW(), NOW()),
(2, 'Acero y Hierro', 'Materiales de acero y hierro', NOW(), NOW()),
(3, 'Madera', 'Materiales de madera', NOW(), NOW()),
(4, 'Herramientas', 'Herramientas de construcción', NOW(), NOW()),
(5, 'Acabados', 'Materiales de acabados', NOW(), NOW());

INSERT INTO `payment_modes` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Efectivo', 'Pago en efectivo', NOW(), NOW()),
(2, 'Transferencia Bancaria', 'Transferencia bancaria', NOW(), NOW()),
(3, 'Cheque', 'Pago con cheque', NOW(), NOW()),
(4, 'Tarjeta de Crédito', 'Pago con tarjeta de crédito', NOW(), NOW());

INSERT INTO `tax_rates` (`id`, `name`, `rate`, `created_at`, `updated_at`) VALUES
(1, 'ISV 15%', 15.00, NOW(), NOW()),
(2, 'Exento', 0.00, NOW(), NOW());

-- Sample construction materials
INSERT INTO `items` (`id`, `name`, `description`, `unit`, `price`, `cost_price`, `item_group_id`, `stock_quantity`, `minimum_stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cemento Portland', 'Cemento Portland tipo I', 'Bolsa', 280.00, 250.00, 1, 100, 20, 'active', NOW(), NOW()),
(2, 'Varilla #4', 'Varilla de hierro #4 de 6m', 'Unidad', 180.00, 160.00, 2, 50, 10, 'active', NOW(), NOW()),
(3, 'Block de 15cm', 'Block de concreto de 15cm', 'Unidad', 12.00, 10.00, 1, 500, 100, 'active', NOW(), NOW()),
(4, 'Arena', 'Arena de río', 'Metro cúbico', 450.00, 400.00, 1, 20, 5, 'active', NOW(), NOW()),
(5, 'Grava', 'Grava triturada', 'Metro cúbico', 500.00, 450.00, 1, 15, 5, 'active', NOW(), NOW());

-- Sample customers
INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `city`, `state`, `country`, `created_at`, `updated_at`) VALUES
(1, 'Constructora San Miguel', 'info@sanmiguel.hn', '+504-9999-1111', 'Col. San Miguel, Tegucigalpa', 'Tegucigalpa', 'Francisco Morazán', 'Honduras', NOW(), NOW()),
(2, 'Desarrollos del Norte', 'contacto@desarrollosnorte.hn', '+504-9999-2222', 'Barrio El Centro, San Pedro Sula', 'San Pedro Sula', 'Cortés', 'Honduras', NOW(), NOW()),
(3, 'Proyectos La Ceiba', 'proyectos@laceiba.hn', '+504-9999-3333', 'Zona Centro, La Ceiba', 'La Ceiba', 'Atlántida', 'Honduras', NOW(), NOW());

-- Sample projects
INSERT INTO `projects` (`id`, `project_name`, `customer_id`, `status`, `start_date`, `deadline`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Residencial Las Torres', 1, 1, '2025-01-15', '2025-12-15', 'Construcción de complejo residencial de 120 apartamentos en 4 torres', NOW(), NOW()),
(2, 'Centro Comercial Plaza Norte', 2, 1, '2025-02-01', '2026-01-31', 'Centro comercial de 3 niveles con 80 locales comerciales', NOW(), NOW()),
(3, 'Complejo Industrial La Ceiba', 3, 0, '2025-03-01', '2025-11-30', 'Complejo industrial con 5 bodegas de 2000m² cada una', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

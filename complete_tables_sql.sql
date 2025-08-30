-- =====================================================
-- TABLAS COMPLETAS: CAI BILLINGS + MÓDULO DE DISEÑO
-- =====================================================

-- =====================================================
-- TABLAS CAI BILLINGS (FACTURACIÓN CAI)
-- =====================================================

-- Tabla principal de facturación CAI
CREATE TABLE `cai_billings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cai_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_rtn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('draft','issued','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `items` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cai_billings_cai_number_unique` (`cai_number`),
  UNIQUE KEY `cai_billings_invoice_number_unique` (`invoice_number`),
  KEY `cai_billings_cai_number_status_index` (`cai_number`,`status`),
  KEY `cai_billings_customer_id_issue_date_index` (`customer_id`,`issue_date`),
  CONSTRAINT `cai_billings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de items de facturación CAI
CREATE TABLE `cai_billing_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cai_billing_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '15.00',
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cai_billing_items_cai_billing_id_index` (`cai_billing_id`),
  KEY `cai_billing_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cai_billing_items_cai_billing_id_foreign` FOREIGN KEY (`cai_billing_id`) REFERENCES `cai_billings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cai_billing_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS MÓDULO DE GENERACIÓN DE DISEÑO
-- =====================================================

-- Tabla de plantillas de diseño
CREATE TABLE `design_templates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1920x1080',
  `default_elements` json DEFAULT NULL,
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `style_properties` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_templates_category_is_active_index` (`category`,`is_active`),
  KEY `design_templates_created_by_foreign` (`created_by`),
  CONSTRAINT `design_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de proyectos de diseño
CREATE TABLE `design_projects` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1920x1080',
  `color_scheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000,#FFFFFF',
  `deadline` date DEFAULT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('draft','in_progress','review','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_design` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_generated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_projects_customer_id_status_index` (`customer_id`,`status`),
  KEY `design_projects_priority_deadline_index` (`priority`,`deadline`),
  KEY `design_projects_status_created_at_index` (`status`,`created_at`),
  KEY `design_projects_template_id_foreign` (`template_id`),
  KEY `design_projects_created_by_foreign` (`created_by`),
  CONSTRAINT `design_projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_projects_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `design_templates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `design_projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de elementos de diseño
CREATE TABLE `design_elements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `design_project_id` bigint(20) UNSIGNED NOT NULL,
  `element_type` enum('text','image','shape','logo','icon') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_x` decimal(8,2) NOT NULL DEFAULT '0.00',
  `position_y` decimal(8,2) NOT NULL DEFAULT '0.00',
  `width` decimal(8,2) NOT NULL,
  `height` decimal(8,2) NOT NULL,
  `layer_order` int(11) NOT NULL DEFAULT '1',
  `style_properties` json DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_elements_design_project_id_layer_order_index` (`design_project_id`,`layer_order`),
  KEY `design_elements_element_type_is_visible_index` (`element_type`,`is_visible`),
  CONSTRAINT `design_elements_design_project_id_foreign` FOREIGN KEY (`design_project_id`) REFERENCES `design_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices para CAI Billings
CREATE INDEX idx_cai_billings_status_date ON cai_billings(status, issue_date);
CREATE INDEX idx_cai_billings_customer_status ON cai_billings(customer_id, status);
CREATE INDEX idx_cai_billings_payment_date ON cai_billings(payment_date);

-- Índices para Design Module
CREATE INDEX idx_design_projects_customer_priority ON design_projects(customer_id, priority);
CREATE INDEX idx_design_projects_status_deadline ON design_projects(status, deadline);
CREATE INDEX idx_design_elements_project_type ON design_elements(design_project_id, element_type);
CREATE INDEX idx_design_templates_category ON design_templates(category);

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

-- Plantillas de diseño predefinidas
INSERT INTO `design_templates` (`name`, `description`, `category`, `dimensions`, `default_elements`, `is_active`, `created_at`, `updated_at`) VALUES
('Plantilla Básica', 'Plantilla básica para diseños generales', 'general', '1920x1080', '[]', 1, NOW(), NOW()),
('Flyer Promocional', 'Plantilla para flyers promocionales', 'marketing', '210x297', '[]', 1, NOW(), NOW()),
('Banner Web', 'Plantilla para banners web', 'web', '1200x300', '[]', 1, NOW(), NOW()),
('Tarjeta de Presentación', 'Plantilla para tarjetas de presentación', 'business', '90x50', '[]', 1, NOW(), NOW()),
('Poster Evento', 'Plantilla para posters de eventos', 'events', '420x594', '[]', 1, NOW(), NOW()),
('Logo Corporativo', 'Plantilla para diseño de logos', 'branding', '500x500', '[]', 1, NOW(), NOW()),
('Brochure Tríptico', 'Plantilla para brochures de 3 paneles', 'marketing', '297x210', '[]', 1, NOW(), NOW()),
('Certificado', 'Plantilla para certificados y diplomas', 'documents', '297x210', '[]', 1, NOW(), NOW());

-- =====================================================
-- TRIGGERS PARA AUTOMATIZACIÓN
-- =====================================================

-- Trigger para generar número de factura automáticamente
DELIMITER $$
CREATE TRIGGER `generate_invoice_number` BEFORE INSERT ON `cai_billings`
FOR EACH ROW BEGIN
    DECLARE next_number INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(invoice_number, 4) AS UNSIGNED)), 0) + 1 
    INTO next_number 
    FROM cai_billings 
    WHERE invoice_number LIKE 'INV%';
    
    SET NEW.invoice_number = CONCAT('INV', LPAD(next_number, 6, '0'));
END$$
DELIMITER ;

-- Trigger para actualizar fecha de completado en proyectos
DELIMITER $$
CREATE TRIGGER `update_completed_date` BEFORE UPDATE ON `design_projects`
FOR EACH ROW BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        SET NEW.completed_at = NOW();
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista para resumen de facturación CAI
CREATE VIEW `cai_billing_summary` AS
SELECT 
    cb.id,
    cb.cai_number,
    cb.invoice_number,
    cb.customer_name,
    cb.issue_date,
    cb.due_date,
    cb.status,
    cb.total_amount,
    c.company_name,
    c.email,
    COUNT(cbi.id) as total_items
FROM cai_billings cb
LEFT JOIN customers c ON cb.customer_id = c.id
LEFT JOIN cai_billing_items cbi ON cb.id = cbi.cai_billing_id
GROUP BY cb.id;

-- Vista para resumen de proyectos de diseño
CREATE VIEW `design_project_summary` AS
SELECT 
    dp.id,
    dp.project_name,
    dp.status,
    dp.priority,
    dp.deadline,
    dp.budget,
    c.company_name as customer_name,
    dt.name as template_name,
    u.name as created_by_name,
    COUNT(de.id) as total_elements,
    dp.created_at
FROM design_projects dp
LEFT JOIN customers c ON dp.customer_id = c.id
LEFT JOIN design_templates dt ON dp.template_id = dt.id
LEFT JOIN users u ON dp.created_by = u.id
LEFT JOIN design_elements de ON dp.id = de.design_project_id
GROUP BY dp.id;

-- =====================================================
-- COMENTARIOS FINALES
-- =====================================================

/*
ESTRUCTURA COMPLETA INCLUYE:

CAI BILLINGS:
- cai_billings: Facturación principal con numeración CAI
- cai_billing_items: Items detallados con cálculos

DESIGN MODULE:
- design_templates: Plantillas reutilizables (8 ejemplos incluidos)
- design_projects: Proyectos con estados y prioridades
- design_elements: Elementos posicionables con propiedades JSON

CARACTERÍSTICAS:
- Triggers automáticos para numeración
- Índices optimizados para rendimiento
- Vistas para reportes rápidos
- Relaciones foreign key completas
- Datos de ejemplo incluidos

ESTADOS DE FACTURA: draft, issued, paid, cancelled
ESTADOS DE PROYECTO: draft, in_progress, review, completed, cancelled
PRIORIDADES: low, medium, high, urgent
TIPOS DE ELEMENTO: text, image, shape, logo, icon
*/

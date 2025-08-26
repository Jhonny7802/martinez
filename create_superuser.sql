-- Script SQL para crear superusuario con todos los permisos
-- Base de datos: martinez
-- Fecha: 2025-08-25

USE martinez;

-- 1. Insertar superusuario en tabla users
INSERT INTO `users` (
    `first_name`, 
    `last_name`, 
    `email`, 
    `phone`,
    `password`, 
    `owner_id`,
    `owner_type`,
    `is_enable`,
    `image`,
    `facebook`,
    `linkedin`,
    `skype`,
    `staff_member`,
    `send_welcome_email`,
    `default_language`,
    `email_verified_at`, 
    `remember_token`,
    `created_at`, 
    `updated_at`,
    `stripe_id`,
    `pm_type`,
    `pm_last_four`,
    `trial_ends_at`
) VALUES (
    'Super',
    'Admin',
    'superadmin@martinez.com',
    '+504-9999-0000',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    NULL,
    NULL,
    1,
    NULL,
    NULL,
    NULL,
    NULL,
    1,
    0,
    'es',
    NOW(),
    NULL,
    NOW(),
    NOW(),
    NULL,
    NULL,
    NULL,
    NULL
) ON DUPLICATE KEY UPDATE 
    `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    `is_enable` = 1,
    `staff_member` = 1,
    `updated_at` = NOW();

-- 2. Crear rol Super Admin si no existe
INSERT INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`) 
VALUES ('Super Admin', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 3. Crear todos los permisos del sistema
INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
('manage_users', 'web', NOW(), NOW()),
('manage_projects', 'web', NOW(), NOW()),
('manage_customers', 'web', NOW(), NOW()),
('manage_items', 'web', NOW(), NOW()),
('manage_invoices', 'web', NOW(), NOW()),
('manage_estimates', 'web', NOW(), NOW()),
('manage_tasks', 'web', NOW(), NOW()),
('manage_tickets', 'web', NOW(), NOW()),
('manage_expenses', 'web', NOW(), NOW()),
('manage_payments', 'web', NOW(), NOW()),
('manage_reports', 'web', NOW(), NOW()),
('manage_settings', 'web', NOW(), NOW()),
('manage_departments', 'web', NOW(), NOW()),
('manage_roles', 'web', NOW(), NOW()),
('manage_permissions', 'web', NOW(), NOW()),
('manage_announcements', 'web', NOW(), NOW()),
('manage_articles', 'web', NOW(), NOW()),
('manage_contacts', 'web', NOW(), NOW()),
('manage_leads', 'web', NOW(), NOW()),
('manage_contracts', 'web', NOW(), NOW()),
('manage_goals', 'web', NOW(), NOW()),
('manage_reminders', 'web', NOW(), NOW()),
('manage_notes', 'web', NOW(), NOW()),
('manage_proposals', 'web', NOW(), NOW()),
('manage_credit_notes', 'web', NOW(), NOW()),
('manage_material_requisitions', 'web', NOW(), NOW()),
('manage_inventory', 'web', NOW(), NOW()),
('manage_internal_messages', 'web', NOW(), NOW()),
('manage_budget_alerts', 'web', NOW(), NOW()),
('manage_progress_reports', 'web', NOW(), NOW()),
('manage_project_activities', 'web', NOW(), NOW()),
('view_dashboard', 'web', NOW(), NOW()),
('view_reports', 'web', NOW(), NOW()),
('export_data', 'web', NOW(), NOW()),
('import_data', 'web', NOW(), NOW()),
('manage_inventory_movements', 'web', NOW(), NOW()),
('manage_suppliers', 'web', NOW(), NOW()),
('manage_categories', 'web', NOW(), NOW()),
('manage_taxes', 'web', NOW(), NOW()),
('manage_currencies', 'web', NOW(), NOW()),
('manage_languages', 'web', NOW(), NOW()),
('manage_email_templates', 'web', NOW(), NOW()),
('manage_notifications', 'web', NOW(), NOW()),
('manage_activity_logs', 'web', NOW(), NOW()),
('manage_backups', 'web', NOW(), NOW()),
('manage_system_settings', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 4. Asignar todos los permisos al rol Super Admin
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
CROSS JOIN `roles` r
WHERE r.name = 'Super Admin'
ON DUPLICATE KEY UPDATE `permission_id` = `permission_id`;

-- 5. Asignar rol Super Admin al usuario
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT r.id, 'App\\Models\\User', u.id
FROM `roles` r
CROSS JOIN `users` u
WHERE r.name = 'Super Admin' AND u.email = 'superadmin@martinez.com'
ON DUPLICATE KEY UPDATE `role_id` = `role_id`;

-- 6. Asignar todos los permisos directamente al usuario (doble seguridad)
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`)
SELECT p.id, 'App\\Models\\User', u.id
FROM `permissions` p
CROSS JOIN `users` u
WHERE u.email = 'superadmin@martinez.com'
ON DUPLICATE KEY UPDATE `permission_id` = `permission_id`;

-- 7. Verificar la creación
SELECT 
    u.id,
    u.email,
    u.first_name,
    u.last_name,
    u.status,
    u.is_admin,
    COUNT(DISTINCT mhr.role_id) as roles_count,
    COUNT(DISTINCT mhp.permission_id) as direct_permissions_count
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
LEFT JOIN model_has_permissions mhp ON u.id = mhp.model_id AND mhp.model_type = 'App\\Models\\User'
WHERE u.email = 'superadmin@martinez.com'
GROUP BY u.id, u.email, u.first_name, u.last_name, u.status, u.is_admin;

-- Mostrar información de acceso
SELECT 
    '=== SUPERUSUARIO CREADO EXITOSAMENTE ===' as mensaje,
    'Email: superadmin@martinez.com' as credencial_1,
    'Password: password' as credencial_2,
    'Estado: Activo' as estado,
    'Permisos: Todos los permisos del sistema' as permisos;

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

try {
    // Lista completa de permisos del sistema
    $permissions = [
        'manage_customers', 'manage_customer_groups', 'manage_staff_member', 'manage_articles', 
        'manage_article_groups', 'manage_tags', 'manage_lead_status', 'manage_lead_sources', 
        'manage_leads', 'manage_tasks', 'manage_tickets', 'manage_ticket_priority', 
        'manage_ticket_statuses', 'manage_predefined_replies', 'manage_projects', 
        'manage_invoices', 'manage_payments', 'manage_credit_notes', 'manage_proposals', 
        'manage_estimates', 'manage_departments', 'manage_expense_category', 'manage_expenses',
        'manage_payment_mode', 'manage_tax_rates', 'manage_announcements', 'manage_items',
        'manage_items_groups', 'manage_materials', 'view_inventory_reports', 'manage_internal_messages',
        'manage_contracts', 'manage_contracts_types', 'manage_goals', 'manage_services',
        'manage_settings', 'manage_budget_controls', 'manage_budget_expenses', 'manage_budget_alerts'
    ];

    // Crear todos los permisos
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission], [
            'name' => $permission,
            'guard_name' => 'web'
        ]);
    }

    // Crear rol SuperAdmin
    $superAdminRole = Role::firstOrCreate(['name' => 'superadmin'], [
        'name' => 'superadmin',
        'guard_name' => 'web'
    ]);

    // Asignar TODOS los permisos al rol SuperAdmin
    $allPermissions = Permission::all();
    $superAdminRole->syncPermissions($allPermissions);

    // Crear/actualizar usuario SuperAdmin
    $superAdmin = User::updateOrCreate(
        ['email' => 'superadmin@martinez.com'],
        [
            'first_name' => 'Super',
            'last_name' => 'Administrador',
            'password' => Hash::make('superadmin123'),
            'email_verified_at' => now(),
            'is_enable' => 1,
        ]
    );

    // Asignar rol al usuario
    $superAdmin->syncRoles(['superadmin']);

    echo "✅ Sistema de permisos configurado completamente:\n";
    echo "📋 Permisos creados: " . $allPermissions->count() . "\n";
    echo "👤 Usuario: superadmin@martinez.com\n";
    echo "🔑 Password: superadmin123\n";
    echo "🎯 Rol: SuperAdmin con acceso total\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

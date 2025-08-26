<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear o actualizar el superusuario
        $email = 'superadmin@martinez.com';
        
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            $this->command->info("Usuario {$email} ya existe. Actualizando permisos...");
            $user = $existingUser;
        } else {
            $input = [
                'first_name' => 'Super',
                'last_name' => 'Administrator',
                'email' => $email,
                'password' => Hash::make('SuperAdmin2024!'),
                'phone' => '+1234567890',
                'is_enable' => true,
                'email_verified_at' => Carbon::now(),
            ];

            $user = User::create($input);
            $this->command->info("Usuario {$email} creado exitosamente.");
        }

        // Crear todos los permisos necesarios si no existen
        $allPermissions = [
            // Permisos originales del sistema
            ['name' => 'manage_customer_groups', 'type' => 'Customers', 'display_name' => 'Manage Customer Groups'],
            ['name' => 'manage_customers', 'type' => 'Customers', 'display_name' => 'Manage Customers'],
            ['name' => 'manage_staff_member', 'type' => 'Members', 'display_name' => 'Manage Staff Member'],
            ['name' => 'manage_article_groups', 'type' => 'Articles', 'display_name' => 'Manage Article Groups'],
            ['name' => 'manage_articles', 'type' => 'Articles', 'display_name' => 'Manage Articles'],
            ['name' => 'manage_tags', 'type' => 'Tags', 'display_name' => 'Manage Tags'],
            ['name' => 'manage_leads', 'type' => 'Leads', 'display_name' => 'Manage Leads'],
            ['name' => 'manage_lead_status', 'type' => 'Leads', 'display_name' => 'Manage Lead Status'],
            ['name' => 'manage_tasks', 'type' => 'Tasks', 'display_name' => 'Manage Tasks'],
            ['name' => 'manage_ticket_priority', 'type' => 'Tickets', 'display_name' => 'Manage Ticket Priority'],
            ['name' => 'manage_ticket_statuses', 'type' => 'Tickets', 'display_name' => 'Manage Ticket Statuses'],
            ['name' => 'manage_tickets', 'type' => 'Tickets', 'display_name' => 'Manage Tickets'],
            ['name' => 'manage_invoices', 'type' => 'Invoices', 'display_name' => 'Manage Invoices'],
            ['name' => 'manage_payments', 'type' => 'Payments', 'display_name' => 'Manage Payments'],
            ['name' => 'manage_payment_mode', 'type' => 'Payments', 'display_name' => 'Manage Payment Mode'],
            ['name' => 'manage_credit_notes', 'type' => 'Credit Note', 'display_name' => 'Manage Credit Note'],
            ['name' => 'manage_proposals', 'type' => 'Proposals', 'display_name' => 'Manage Proposals'],
            ['name' => 'manage_estimates', 'type' => 'Estimates', 'display_name' => 'Manage Estimates'],
            ['name' => 'manage_departments', 'type' => 'Departments', 'display_name' => 'Manage Departments'],
            ['name' => 'manage_predefined_replies', 'type' => 'Predefined Replies', 'display_name' => 'Manage Predefined Replies'],
            ['name' => 'manage_expense_category', 'type' => 'Expenses', 'display_name' => 'Manage Expense Category'],
            ['name' => 'manage_expenses', 'type' => 'Expenses', 'display_name' => 'Manage Expenses'],
            ['name' => 'manage_services', 'type' => 'Services', 'display_name' => 'Manage Services'],
            ['name' => 'manage_items', 'type' => 'Items', 'display_name' => 'Manage Items'],
            ['name' => 'manage_items_groups', 'type' => 'Items', 'display_name' => 'Manage Items Groups'],
            ['name' => 'manage_tax_rates', 'type' => 'TaxRate', 'display_name' => 'Manage Tax Rates'],
            ['name' => 'manage_announcements', 'type' => 'Announcements', 'display_name' => 'Manage Announcements'],
            ['name' => 'manage_calenders', 'type' => 'Calenders', 'display_name' => 'Manage Calenders'],
            ['name' => 'manage_lead_sources', 'type' => 'Leads', 'display_name' => 'Manage Lead Sources'],
            ['name' => 'manage_contracts_types', 'type' => 'Contracts', 'display_name' => 'Manage Contract Types'],
            ['name' => 'manage_contracts', 'type' => 'Contracts', 'display_name' => 'Manage Contracts'],
            ['name' => 'manage_projects', 'type' => 'Projects', 'display_name' => 'Manage Projects'],
            ['name' => 'manage_goals', 'type' => 'Goals', 'display_name' => 'Manage Goals'],
            ['name' => 'manage_settings', 'type' => 'Settings', 'display_name' => 'Manage Settings'],
            ['name' => 'contact_projects', 'type' => 'Contacts', 'display_name' => 'Contact Projects'],
            ['name' => 'contact_invoices', 'type' => 'Contacts', 'display_name' => 'Contact Invoices'],
            ['name' => 'contact_proposals', 'type' => 'Contacts', 'display_name' => 'Contact Proposals'],
            ['name' => 'contact_contracts', 'type' => 'Contacts', 'display_name' => 'Contact Contracts'],
            ['name' => 'contact_estimates', 'type' => 'Contacts', 'display_name' => 'Contact Estimates'],
            ['name' => 'contact_tickets', 'type' => 'Contacts', 'display_name' => 'Contact Tickets'],
            
            // Permisos adicionales para módulos específicos del sistema
            ['name' => 'manage_material_requisitions', 'type' => 'Material Requisitions', 'display_name' => 'Manage Material Requisitions'],
            ['name' => 'approve_material_requisitions', 'type' => 'Material Requisitions', 'display_name' => 'Approve Material Requisitions'],
            ['name' => 'manage_budget_controls', 'type' => 'Budget', 'display_name' => 'Manage Budget Controls'],
            ['name' => 'manage_budget_alerts', 'type' => 'Budget', 'display_name' => 'Manage Budget Alerts'],
            ['name' => 'manage_budget_expenses', 'type' => 'Budget', 'display_name' => 'Manage Budget Expenses'],
            ['name' => 'view_budget_reports', 'type' => 'Budget', 'display_name' => 'View Budget Reports'],
            ['name' => 'manage_construction_dashboard', 'type' => 'Dashboard', 'display_name' => 'Manage Construction Dashboard'],
            ['name' => 'manage_roles', 'type' => 'Roles', 'display_name' => 'Manage Roles'],
            ['name' => 'manage_permissions', 'type' => 'Permissions', 'display_name' => 'Manage Permissions'],
            ['name' => 'manage_users', 'type' => 'Users', 'display_name' => 'Manage Users'],
            ['name' => 'view_activity_logs', 'type' => 'Logs', 'display_name' => 'View Activity Logs'],
            
            // Permisos para el módulo de Mensajería Interna
            ['name' => 'view_internal_messages', 'type' => 'Messages', 'display_name' => 'View Internal Messages'],
            ['name' => 'send_internal_messages', 'type' => 'Messages', 'display_name' => 'Send Internal Messages'],
            ['name' => 'delete_internal_messages', 'type' => 'Messages', 'display_name' => 'Delete Internal Messages'],
            ['name' => 'manage_message_templates', 'type' => 'Messages', 'display_name' => 'Manage Message Templates'],
            ['name' => 'view_message_reports', 'type' => 'Messages', 'display_name' => 'View Message Reports'],
            ['name' => 'manage_internal_messages', 'type' => 'Messages', 'display_name' => 'Manage Internal Messages'],
            ['name' => 'reply_internal_messages', 'type' => 'Messages', 'display_name' => 'Reply Internal Messages'],
            ['name' => 'forward_internal_messages', 'type' => 'Messages', 'display_name' => 'Forward Internal Messages'],
            ['name' => 'archive_internal_messages', 'type' => 'Messages', 'display_name' => 'Archive Internal Messages'],
            ['name' => 'mark_messages_read', 'type' => 'Messages', 'display_name' => 'Mark Messages as Read'],
        ];

        // Crear permisos que no existan
        foreach ($allPermissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Obtener todos los permisos disponibles
        $permissions = Permission::all();
        
        $this->command->info("Total de permisos encontrados: " . $permissions->count());

        // Obtener o crear el rol admin
        $adminRole = Role::whereName('admin')->first();
        
        if (!$adminRole) {
            $adminRole = Role::create([
                'name' => 'admin',
                'display_name' => 'Admin',
                'is_default' => true,
            ]);
            $this->command->info('Rol admin creado.');
        }

        // Asignar el rol admin al usuario
        if (!$user->hasRole('admin')) {
            $user->assignRole($adminRole);
            $this->command->info('Rol admin asignado al usuario.');
        }

        // Dar todos los permisos al rol admin
        $adminRole->syncPermissions($permissions);
        $this->command->info('Todos los permisos asignados al rol admin.');

        // Dar todos los permisos directamente al usuario (doble seguridad)
        $user->syncPermissions($permissions);
        $this->command->info('Todos los permisos asignados directamente al usuario.');

        $this->command->info('=== SUPERUSUARIO CREADO EXITOSAMENTE ===');
        $this->command->info("Email: {$email}");
        $this->command->info("Password: SuperAdmin2024!");
        $this->command->info("Permisos totales: " . $permissions->count());
        $this->command->info("Rol: admin");
        
        // Mostrar todos los permisos asignados
        $this->command->info("\n=== PERMISOS ASIGNADOS ===");
        foreach ($permissions as $permission) {
            $this->command->info("- {$permission->display_name} ({$permission->name})");
        }
    }
}

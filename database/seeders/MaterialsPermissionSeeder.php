<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MaterialsPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create materials management permissions
        $permissions = [
            'manage_materials' => 'Gestionar Materiales e Inventario',
            'view_materials' => 'Ver Materiales',
            'create_materials' => 'Crear Materiales',
            'edit_materials' => 'Editar Materiales',
            'delete_materials' => 'Eliminar Materiales',
            'manage_requisitions' => 'Gestionar Requisiciones',
            'approve_requisitions' => 'Aprobar Requisiciones',
            'deliver_materials' => 'Entregar Materiales',
            'adjust_inventory' => 'Ajustar Inventario',
            'view_inventory_reports' => 'Ver Reportes de Inventario'
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayName]
            );
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $workerRole = Role::where('name', 'worker')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($permissions));
        }

        if ($managerRole) {
            $managerRole->givePermissionTo([
                'manage_materials',
                'view_materials',
                'create_materials',
                'edit_materials',
                'manage_requisitions',
                'approve_requisitions',
                'deliver_materials',
                'adjust_inventory',
                'view_inventory_reports'
            ]);
        }

        if ($supervisorRole) {
            $supervisorRole->givePermissionTo([
                'view_materials',
                'create_materials',
                'edit_materials',
                'manage_requisitions',
                'deliver_materials',
                'view_inventory_reports'
            ]);
        }

        if ($workerRole) {
            $workerRole->givePermissionTo([
                'view_materials',
                'manage_requisitions'
            ]);
        }

        $this->command->info('Materials permissions seeder completed successfully!');
    }
}

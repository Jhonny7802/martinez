<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear o actualizar el super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@martinez.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@martinez.com',
                'password' => Hash::make('superadmin123'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Obtener o crear el rol de super admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // Obtener todos los permisos disponibles
        $allPermissions = Permission::all();

        // Asignar todos los permisos al rol super admin
        $superAdminRole->syncPermissions($allPermissions);

        // Asignar el rol super admin al usuario
        $superAdmin->assignRole($superAdminRole);

        // También asignar directamente todos los permisos al usuario
        $superAdmin->syncPermissions($allPermissions);

        $this->command->info('✅ Super Admin creado exitosamente');
        $this->command->info('📧 Email: superadmin@martinez.com');
        $this->command->info('🔑 Password: superadmin123');
        $this->command->info('🛡️ Permisos: ' . $allPermissions->count() . ' permisos asignados');
    }
}

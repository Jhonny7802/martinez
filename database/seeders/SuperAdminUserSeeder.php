<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear rol de superadmin si no existe
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin'], [
            'name' => 'superadmin',
            'guard_name' => 'web',
            'description' => 'Super Administrador con acceso total'
        ]);

        // Asignar todos los permisos al rol de superadmin
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Crear usuario superadmin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@martinez.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@martinez.com',
                'password' => Hash::make('superadmin123'),
                'phone' => '12345678',
                'email_verified_at' => Carbon::now(),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Asignar rol de superadmin al usuario
        $superAdmin->assignRole($superAdminRole);

        // Asignar también el rol de admin por compatibilidad
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $superAdmin->assignRole($adminRole);
        }

        // Crear permisos específicos para presupuestos si no existen
        $budgetPermissions = [
            'manage_budget_controls' => 'Gestionar controles de presupuesto',
            'manage_budget_expenses' => 'Gestionar gastos de presupuesto',
            'manage_budget_alerts' => 'Gestionar alertas de presupuesto',
        ];

        foreach ($budgetPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'guard_name' => 'web',
                    'description' => $description
                ]
            );
        }

        // Actualizar los permisos del rol superadmin
        $superAdminRole->syncPermissions(Permission::all());

        // Actualizar los permisos del rol admin
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        $this->command->info('Usuario SuperAdmin creado exitosamente con acceso total.');
        $this->command->info('Email: superadmin@martinez.com');
        $this->command->info('Contraseña: superadmin123');
    }
}

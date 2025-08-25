<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'martinez:create-superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un usuario superadmin con todos los permisos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creando usuario superadmin...');

        // Crear usuario superadmin
        $user = User::updateOrCreate(
            ['email' => 'superadmin@martinez.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@martinez.com',
                'password' => Hash::make('superadmin123'),
                'phone' => '12345678',
                'email_verified_at' => Carbon::now(),
                'is_active' => true,
            ]
        );

        $this->info('Usuario superadmin creado o actualizado correctamente.');

        // Asignar rol de admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
            $this->info('Rol admin asignado al usuario superadmin.');
        } else {
            $this->error('No se encontró el rol admin.');
        }

        // Asignar todos los permisos directamente al usuario
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $user->givePermissionTo($permission->name);
        }

        $this->info('Todos los permisos asignados al usuario superadmin.');

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
            // Asegurar que el usuario tenga estos permisos
            $user->givePermissionTo($name);
        }

        $this->info('Permisos de presupuesto creados y asignados correctamente.');

        $this->info('¡ÉXITO! Usuario SuperAdmin creado exitosamente con acceso total.');
        $this->info('Email: superadmin@martinez.com');
        $this->info('Contraseña: superadmin123');

        return 0;
    }
}

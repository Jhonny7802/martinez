<?php

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Cargar el entorno de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

try {
    // Crear rol de superadmin si no existe
    $superAdminRole = Role::firstOrCreate(['name' => 'superadmin'], [
        'name' => 'superadmin',
        'guard_name' => 'web',
        'description' => 'Super Administrador con acceso total'
    ]);

    echo "Rol superadmin creado o encontrado correctamente.\n";

    // Asignar todos los permisos al rol de superadmin
    $permissions = Permission::all();
    $superAdminRole->syncPermissions($permissions);
    
    echo "Permisos asignados al rol superadmin.\n";

    // Crear usuario superadmin
    $superAdmin = User::updateOrCreate(
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

    echo "Usuario superadmin creado o actualizado correctamente.\n";

    // Asignar rol de superadmin al usuario
    $superAdmin->syncRoles([$superAdminRole->id]);

    // Asignar también el rol de admin por compatibilidad
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        $superAdmin->assignRole($adminRole);
        echo "Rol admin asignado al usuario superadmin.\n";
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

    echo "Permisos de presupuesto creados correctamente.\n";

    // Actualizar los permisos del rol superadmin
    $superAdminRole->syncPermissions(Permission::all());
    echo "Permisos actualizados para el rol superadmin.\n";

    // Actualizar los permisos del rol admin
    if ($adminRole) {
        $adminRole->syncPermissions(Permission::all());
        echo "Permisos actualizados para el rol admin.\n";
    }

    echo "¡ÉXITO! Usuario SuperAdmin creado exitosamente con acceso total.\n";
    echo "Email: superadmin@martinez.com\n";
    echo "Contraseña: superadmin123\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . " en la línea " . $e->getLine() . "\n";
}

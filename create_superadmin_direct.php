<?php

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Cargar el entorno de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Crear usuario superadmin
$user = User::updateOrCreate(
    ['email' => 'superadmin@martinez.com'],
    [
        'first_name' => 'Super',
        'last_name' => 'Admin',
        'email' => 'superadmin@martinez.com',
        'password' => Hash::make('superadmin123'),
        'phone' => '12345678',
        'email_verified_at' => now(),
        'is_active' => true,
    ]
);

echo "Usuario superadmin creado o actualizado correctamente.\n";

// Asignar rol de admin
$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    $user->assignRole($adminRole);
    echo "Rol admin asignado al usuario superadmin.\n";
} else {
    echo "No se encontró el rol admin.\n";
}

// Asignar todos los permisos directamente al usuario
$permissions = Permission::all();
foreach ($permissions as $permission) {
    try {
        $user->givePermissionTo($permission->name);
    } catch (Exception $e) {
        echo "Error al asignar permiso {$permission->name}: {$e->getMessage()}\n";
    }
}

echo "Todos los permisos asignados al usuario superadmin.\n";

// Crear permisos específicos para presupuestos si no existen
$budgetPermissions = [
    'manage_budget_controls' => 'Gestionar controles de presupuesto',
    'manage_budget_expenses' => 'Gestionar gastos de presupuesto',
    'manage_budget_alerts' => 'Gestionar alertas de presupuesto',
];

foreach ($budgetPermissions as $name => $description) {
    try {
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
        echo "Permiso {$name} creado y asignado correctamente.\n";
    } catch (Exception $e) {
        echo "Error con permiso {$name}: {$e->getMessage()}\n";
    }
}

echo "\n¡ÉXITO! Usuario SuperAdmin creado exitosamente con acceso total.\n";
echo "Email: superadmin@martinez.com\n";
echo "Contraseña: superadmin123\n";

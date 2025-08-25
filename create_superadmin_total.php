<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

try {
    // Crear o actualizar usuario SuperAdmin
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

    // Crear rol SuperAdmin si no existe
    $superAdminRole = Role::firstOrCreate(['name' => 'superadmin'], [
        'name' => 'superadmin',
        'guard_name' => 'web'
    ]);

    // Obtener TODOS los permisos disponibles
    $allPermissions = Permission::all();
    
    // Asignar TODOS los permisos al rol SuperAdmin
    $superAdminRole->syncPermissions($allPermissions);
    
    // Asignar rol SuperAdmin al usuario
    $superAdmin->assignRole('superadmin');

    echo "✅ SuperAdmin creado exitosamente:\n";
    echo "Email: superadmin@martinez.com\n";
    echo "Password: superadmin123\n";
    echo "Permisos: " . $allPermissions->count() . " permisos asignados\n";
    echo "Rol: SuperAdmin con acceso total\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

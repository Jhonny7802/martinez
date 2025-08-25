<?php

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Cargar el entorno de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

try {
    // Insertar usuario directamente en la base de datos
    $userId = DB::table('users')->insertGetId([
        'first_name' => 'Super',
        'last_name' => 'Admin',
        'email' => 'superadmin@martinez.com',
        'password' => Hash::make('superadmin123'),
        'phone' => '12345678',
        'email_verified_at' => Carbon::now(),
        'is_active' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    echo "Usuario creado con ID: $userId\n";

    // Obtener el ID del rol admin
    $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
    
    if ($adminRoleId) {
        // Asignar rol admin al usuario
        DB::table('model_has_roles')->insert([
            'role_id' => $adminRoleId,
            'model_type' => 'App\\Models\\User',
            'model_id' => $userId
        ]);
        
        echo "Rol admin asignado al usuario\n";
    } else {
        echo "No se encontró el rol admin\n";
    }

    echo "\n¡ÉXITO! Usuario SuperAdmin creado exitosamente.\n";
    echo "Email: superadmin@martinez.com\n";
    echo "Contraseña: superadmin123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

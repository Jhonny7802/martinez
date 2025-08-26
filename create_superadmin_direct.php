<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configurar la conexión a la base de datos
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'martinez',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Verificar conexión
    echo "Verificando conexión a la base de datos...\n";
    $tables = Capsule::select("SHOW TABLES");
    echo "Conexión exitosa. Tablas encontradas: " . count($tables) . "\n";
    
    // Verificar tabla users
    $userTableExists = Capsule::select("SHOW TABLES LIKE 'users'");
    if (empty($userTableExists)) {
        echo "Error: La tabla 'users' no existe.\n";
        exit(1);
    }
    echo "Tabla 'users' encontrada.\n";
    
    // Verificar usuarios existentes
    $existingUsers = Capsule::table('users')->get();
    echo "Usuarios existentes: " . count($existingUsers) . "\n";
    foreach ($existingUsers as $user) {
        echo "- ID: {$user->id}, Email: {$user->email}, Nombre: {$user->first_name} {$user->last_name}\n";
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

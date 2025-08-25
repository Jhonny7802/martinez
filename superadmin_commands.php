<?php

$user = new \App\Models\User();
$user->first_name = 'Super';
$user->last_name = 'Admin';
$user->email = 'superadmin@martinez.com';
$user->password = Hash::make('superadmin123');
$user->phone = '12345678';
$user->email_verified_at = now();
$user->is_active = true;
$user->save();

// Asignar rol de admin
$adminRole = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
$user->assignRole($adminRole);

// Asignar todos los permisos directamente al usuario
$permissions = \Spatie\Permission\Models\Permission::all();
foreach ($permissions as $permission) {
    $user->givePermissionTo($permission->name);
}

echo "Usuario superadmin creado con éxito!";

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si el usuario ya existe
        $existingUser = User::where('email', 'admin@gmail.com')->first();
        
        if ($existingUser) {
            $this->command->info('Usuario admin@gmail.com ya existe. Actualizando permisos...');
            $user = $existingUser;
        } else {
            $input = [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'phone' => '+917878454512',
                'is_enable' => true,
                'email_verified_at' => Carbon::now(),
            ];

            $user = User::create($input);
            $this->command->info('Usuario admin@gmail.com creado exitosamente.');
        }

        /** @var \App\Models\Permission $permissions */
        $permissions = \App\Models\Permission::all();

        /** @var \App\Models\Role $adminRole */
        $adminRole = \App\Models\Role::whereName('admin')->first();
        
        if ($adminRole) {
            $user->assignRole($adminRole);
            $adminRole->givePermissionTo($permissions);
            $user->givePermissionTo($permissions);
            $this->command->info('Permisos asignados correctamente al usuario admin.');
        } else {
            $this->command->error('Rol admin no encontrado. Ejecute primero el seeder de roles.');
        }
    }
}

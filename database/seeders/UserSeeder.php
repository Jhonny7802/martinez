<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run()
    {
        // Create sample users for testing
        $users = [
            [
                'name' => 'Administrador Martinez',
                'email' => 'admin@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ],
            [
                'name' => 'Carlos Supervisor',
                'email' => 'supervisor@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'supervisor'
            ],
            [
                'name' => 'Ana Gerente',
                'email' => 'gerente@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'manager'
            ],
            [
                'name' => 'Luis Almacenista',
                'email' => 'almacen@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'employee'
            ],
            [
                'name' => 'María Contadora',
                'email' => 'contadora@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'accountant'
            ],
            [
                'name' => 'Pedro Ingeniero',
                'email' => 'ingeniero@martinez.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'engineer'
            ],
        ];

        foreach ($users as $userData) {
            // Split name into first_name and last_name (simple split by first space)
            $parts = preg_split('/\s+/', trim($userData['name']), 2);
            $firstName = $parts[0] ?? $userData['name'];
            $lastName = $parts[1] ?? null;

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => $userData['password'],
                    'email_verified_at' => $userData['email_verified_at'],
                ]
            );

            // Assign role if it exists
            if (Role::where('name', $userData['role'])->exists()) {
                $user->assignRole($userData['role']);
            }
        }

        $this->command->info('Users seeded successfully!');
    }
}

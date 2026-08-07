<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create test users for each role
        $users = [
            [
                'name' => 'Regular User',
                'email' => 'user@test.com',
                'password' => bcrypt('password'),
                'role' => 'User',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Dean User',
                'email' => 'dean@test.com',
                'password' => bcrypt('password'),
                'role' => 'Dean',
            ],
            [
                'name' => 'Vice President User',
                'email' => 'vicepresident@test.com',
                'password' => bcrypt('password'),
                'role' => 'Vice President',
            ],
            [
                'name' => 'SUC President User',
                'email' => 'sucpresident@test.com',
                'password' => bcrypt('password'),
                'role' => 'SUC President',
            ],
            [
                'name' => 'Motor Pool Staff',
                'email' => 'motorpool@test.com',
                'password' => bcrypt('password'),
                'role' => 'Motor Pool',
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@test.com',
                'password' => bcrypt('password'),
                'role' => 'Super Admin',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            $user = \App\Models\User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->assignRole($role);
        }

        $this->call([
            VehicleSeeder::class,
            DriverSeeder::class,
            TripRequestSeeder::class,
        ]);
    }
}
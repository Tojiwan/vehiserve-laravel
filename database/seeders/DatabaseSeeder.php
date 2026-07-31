<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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
                'name' => 'Motor Pool Staff',
                'email' => 'staff@test.com',
                'password' => bcrypt('password'),
                'role' => 'Staff',
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
                'name' => 'VP User',
                'email' => 'vp@test.com',
                'password' => bcrypt('password'),
                'role' => 'VP',
            ],
            [
                'name' => 'SUC President',
                'email' => 'suc@test.com',
                'password' => bcrypt('password'),
                'role' => 'SUC',
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
            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->assignRole($role);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Vehicle Request permissions
            'view vehicle requests',
            'create vehicle requests',
            'edit vehicle requests',
            'delete vehicle requests',
            'approve vehicle requests',
            'reject vehicle requests',
            'assign vehicle driver',
            'view all vehicle requests',

            // Travel Request permissions
            'view travel requests',
            'create travel requests',
            'edit travel requests',
            'delete travel requests',
            'approve travel requests',
            'reject travel requests',
            'view all travel requests',

            // Document Tracking permissions
            'view document tracking',
            'view all documents',

            // Vehicle Management permissions
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',
            'manage vehicle status',

            // Driver Management permissions
            'view drivers',
            'create drivers',
            'edit drivers',
            'delete drivers',

            // User Management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',

            // Calendar & Schedule permissions
            'view calendar',
            'manage calendar',
            'view schedule list',

            // Date Status permissions
            'view date status',
            'manage date status',

            // Attendance permissions
            'view attendance',
            'manage attendance',
            'view attendance reports',

            // Reports permissions
            'view reports',
            'export reports',

            // Settings permissions
            'view settings',
            'manage settings',

            // Audit Logs permissions
            'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $user = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $user->syncPermissions([
            'view vehicle requests',
            'create vehicle requests',
            'edit vehicle requests',
            'delete vehicle requests',
            'view travel requests',
            'create travel requests',
            'edit travel requests',
            'delete travel requests',
            'view document tracking',
            'view vehicles',
        ]);

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'assign vehicle driver',
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',
            'manage vehicle status',
            'view drivers',
            'create drivers',
            'edit drivers',
            'delete drivers',
            'view calendar',
            'manage calendar',
            'view schedule list',
            'view date status',
            'manage date status',
            'view attendance',
            'manage attendance',
            'view attendance reports',
            'view reports',
            'export reports',
        ]);

        $dean = Role::firstOrCreate(['name' => 'Dean', 'guard_name' => 'web']);
        $dean->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'approve travel requests',
            'reject travel requests',
        ]);

        $vp = Role::firstOrCreate(['name' => 'VP', 'guard_name' => 'web']);
        $vp->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'approve travel requests',
            'reject travel requests',
        ]);

        $suc = Role::firstOrCreate(['name' => 'SUC', 'guard_name' => 'web']);
        $suc->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'approve travel requests',
            'reject travel requests',
        ]);

        $motorPool = Role::firstOrCreate(['name' => 'Motor Pool', 'guard_name' => 'web']);
        $motorPool->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'assign vehicle driver',
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',
            'manage vehicle status',
            'view drivers',
            'create drivers',
            'edit drivers',
            'delete drivers',
        ]);

        $vicePresident = Role::firstOrCreate(['name' => 'Vice President', 'guard_name' => 'web']);
        $vicePresident->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'approve travel requests',
            'reject travel requests',
        ]);

        $sucPresident = Role::firstOrCreate(['name' => 'SUC President', 'guard_name' => 'web']);
        $sucPresident->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'approve travel requests',
            'reject travel requests',
        ]);

        $motorPoolFinal = Role::firstOrCreate(['name' => 'Motor Pool (Final)', 'guard_name' => 'web']);
        $motorPoolFinal->syncPermissions([
            'view all vehicle requests',
            'view all travel requests',
            'view document tracking',
            'view all documents',
            'approve vehicle requests',
            'reject vehicle requests',
            'assign vehicle driver',
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',
            'manage vehicle status',
            'view drivers',
            'create drivers',
            'edit drivers',
            'delete drivers',
        ]);

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
    }
}
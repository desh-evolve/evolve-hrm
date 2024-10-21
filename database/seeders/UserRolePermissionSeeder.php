<?php

namespace  Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role', 'type' => 'Roles']);
        Permission::create(['name' => 'create role', 'type' => 'Roles']);
        Permission::create(['name' => 'update role', 'type' => 'Roles']);
        Permission::create(['name' => 'delete role', 'type' => 'Roles']);

        Permission::create(['name' => 'view permission', 'type' => 'Permission']);
        Permission::create(['name' => 'create permission', 'type' => 'Permission']);
        Permission::create(['name' => 'update permission', 'type' => 'Permission']);
        Permission::create(['name' => 'delete permission', 'type' => 'Permission']);

        Permission::create(['name' => 'view user', 'type' => 'User']);
        Permission::create(['name' => 'create user', 'type' => 'User']);
        Permission::create(['name' => 'update user', 'type' => 'User']);
        Permission::create(['name' => 'delete user', 'type' => 'User']);

        Permission::create(['name' => 'view location', 'type' => 'Location']);
        Permission::create(['name' => 'create location', 'type' => 'Location']);
        Permission::create(['name' => 'update location', 'type' => 'Location']);
        Permission::create(['name' => 'delete location', 'type' => 'Location']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role', 'delete role']);
        $adminRole->givePermissionTo(['create permission', 'view permission', 'delete permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user', 'delete user']);
        $adminRole->givePermissionTo(['create location', 'view location', 'update location', 'delete location']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('12345678'),
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('12345678'),
                        ]);

        $staffUser->assignRole($staffRole);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles (if using separate roles table)
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'description' => 'Full system access, can manage everything',
                'level' => 100
            ],
            [
                'name' => 'superadmin',
                'display_name' => 'Super Admin',
                'description' => 'Almost full access, except owner settings',
                'level' => 80
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Can manage users and content',
                'level' => 60
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Limited access, can view and create content',
                'level' => 40
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        // Create sample users with different roles
        $users = [
            [
                'name' => 'Owner User',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'status' => 'active'
            ],
            [
                'name' => 'Super Admin User',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'status' => 'active'
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active'
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

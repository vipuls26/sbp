<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
    {
        // Seed roles first so users can reference them.
        $this->call([RoleSeeder::class]);

        $userRoleId = Role::where('name', 'user')->value('id');
        $adminRoleId = Role::where('name', 'admin')->value('id');

        // Keep demo users stable across repeated seed runs.
        $users = [
            ['name' => 'testuser1', 'email' => 'user1@test.com', 'role_id' => $userRoleId],
            ['name' => 'testuser2', 'email' => 'user2@test.com', 'role_id' => $userRoleId],
            ['name' => 'testuser3', 'email' => 'user3@test.com', 'role_id' => $userRoleId],
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'role_id' => $adminRoleId],
            ['name' => 'testuser4', 'email' => 'user4@test.com', 'role_id' => $userRoleId],
            ['name' => 'testuser5', 'email' => 'user5@test.com', 'role_id' => $userRoleId],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => 'password',
                    'role_id' => $userData['role_id'],
                ]
            );
        }

        $this->call([PlanSeeder::class]);
    }
}

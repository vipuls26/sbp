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
        $this->call([RoleSeeder::class]);

        // user1
        User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        // user2
        User::factory()->create([
            'name' => 'user2',
            'email' => 'user2@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        // user3
        User::factory()->create([
            'name' => 'user3',
            'email' => 'user3@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);


        // admin user
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role_id' => 2
        ]);


        // user4
        User::factory()->create([
            'name' => 'user4',
            'email' => 'user4@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        // user5
        User::factory()->create([
            'name' => 'user5',
            'email' => 'user5@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        $this->call([PlanSeeder::class]);
    }
}

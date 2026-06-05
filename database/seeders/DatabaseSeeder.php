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

        User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'user2',
            'email' => 'user2@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'user3',
            'email' => 'user3@gmail.com',
            'password' => 'password',
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role_id' => 2
        ]);

        $this->call([PlanSeeder::class]);
    }
}

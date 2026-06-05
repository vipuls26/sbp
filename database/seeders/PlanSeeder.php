<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::insert([
            // monthly plan
            ['name' => 'Hobby', 'description' => 'Perfect for individuals and beginners.', 'pricing' => 49, 'duration' => 'monthly', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basic', 'description' => 'Best for small projects.', 'pricing' => 149, 'duration' => 'monthly', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pro', 'description' => 'Limited access to feature', 'pricing' => 499, 'duration' => 'monthly', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // annual plan
            ['name' => 'Hobby', 'description' => 'Perfect for individuals and beginners.', 'pricing' => 599, 'duration' => 'annual', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basic', 'description' => 'Best for small projects.', 'pricing' => 1499, 'duration' => 'annual', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pro', 'description' => 'Unlimited access to feature', 'pricing' => 2999, 'duration' => 'annual', 'admin_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

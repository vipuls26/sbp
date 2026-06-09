<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Keep role seeding safe to run more than once.
        foreach (['user', 'admin', 'other'] as $roleName) {
            Role::updateOrCreate(
                ['name' => $roleName],
                ['name' => $roleName]
            );
        }
    }
}

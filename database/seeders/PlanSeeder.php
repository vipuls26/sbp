<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Services\Stripe\StripePlanCatalogService;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@gmail.com')->value('id');

        if (! $adminId) {
            throw new InvalidArgumentException(
                'Admin user not found. Seed roles and users first.'
            );
        }

        $stripePlans = app(StripePlanCatalogService::class)->sync();

        foreach ($stripePlans as $plan) {

            $features = match (strtolower($plan['name'])) {
                'hobby' => [
                    'project' => true,
                    'team_management' => false,
                    'analytics' => false,
                ],

                'basic' => [
                    'project' => true,
                    'team_management' => true,
                    'analytics' => false,
                ],

                'pro' => [
                    'project' => true,
                    'team_management' => true,
                    'analytics' => true,
                ],

                default => [],
            };

            Plan::updateOrCreate(
                [
                    'name' => $plan['name'],
                    'duration' => $plan['duration'],
                ],
                [
                    'description' => $plan['description'],
                    'features' => $features,
                    'pricing' => $plan['pricing'],
                    'duration' => $plan['duration'],
                    'stripe_price_id' => $plan['stripe_price_id'],
                    'stripe_product_id' => $plan['stripe_product_id'],
                    'admin_id' => $adminId,
                    'is_active' => 'true',
                ]
            );
        }
    }
}

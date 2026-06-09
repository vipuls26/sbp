<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\PlanSeeder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('stripe:sync-plans', function () {
    $this->call('db:seed', [
        '--class' => PlanSeeder::class,
    ]);

    $this->info('Stripe products and prices have been synced, and local plans were updated.');
})->purpose('Create Stripe products and prices for the demo plans');

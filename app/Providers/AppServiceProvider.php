<?php

namespace App\Providers;

use App\Interfaces\Auth\UserRepositoryInterface;
use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Repositories\Auth\UserRepository;
use App\Repositories\Plan\PlanRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

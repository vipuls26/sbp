<?php

namespace App\Providers;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Interfaces\Auth\UserRepositoryInterface;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Auth\UserRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Plan\PlanRepository;
use App\Repositories\Subscription\SubscriptionRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use App\Models\Subscription;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);
    }
}

<?php

namespace App\Providers;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Interfaces\Auth\UserRepositoryInterface;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Interfaces\Team\TeamRepositoryInterface;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Auth\UserRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Plan\PlanRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Repositories\Team\TeamRepository;
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
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useSubscriptionModel(Subscription::class);
    }
}

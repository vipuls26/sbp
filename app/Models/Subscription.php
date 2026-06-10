<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'cancel_at_period_end' => 'boolean',
        ];
    }

    // subscription belongs to the subscriber in our app
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // plan belongs to the selected package
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // logged-in user subscription
    #[Scope]
    protected function activeSubscription(Builder $query): void
    {
        if (! Auth::check()) {
            return;
        }

        $query->where('user_id', Auth::id())
            ->where('end_date', '>', now());
    }

    // subscription expired near by date
    #[Scope]
    protected function expireSubscription(Builder $query, int $days): Builder
    {
        return $query->where('end_date', '<', now()->addDays($days)->toDateString());
    }

    // total earning from all subscriptions
    #[Scope]
    protected function totalEarning(Builder $query): Builder
    {
        return $query
            ->leftJoin('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->selectRaw('COALESCE(SUM(plans.pricing), 0) as total_earning');
    }
}

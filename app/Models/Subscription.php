<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

#[Fillable(['plan_id', 'subscriber_id', 'start_date', 'end_date', 'status'])]
class Subscription extends Model
{
    // subscription belongs to the subscriber
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    // subscription belongs to a plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }


    // logged-in use subscription
    #[Scope]
    protected function activeSubscription(Builder $query): void
    {
        if (! Auth::check()) {
            return;
        }

        $query->where('subscriber_id', Auth::id())
            ->where('end_date', '>', now());
    }


    // subscription expried
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

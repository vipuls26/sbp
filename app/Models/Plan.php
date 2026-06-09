<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

#[Fillable(['name', 'description', 'pricing', 'duration', 'is_active', 'admin_id', 'stripe_price_id', 'stripe_product_id'])]
class Plan extends Model
{
    use SoftDeletes;

    // plan created by an admin user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // plan has many subscription
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // plan not subscib by user
    #[Scope]
    protected function notSubscribed(Builder $query): void
    {
        if (! Auth::check()) {
            return;
        }

        $query->whereDoesntHave('subscriptions', function ($query) {
            $query->where('user_id', Auth::id());
        });
    }

    // active plan of user
    #[Scope]
    protected function activeSubscription(Builder $query): void
    {
        if (! Auth::check()) {
            return;
        }

        $query->whereHas('subscriptions', function ($query) {
            $query->where('user_id', Auth::id());
            $query->where('end_date', '>', now());
        });
    }
}

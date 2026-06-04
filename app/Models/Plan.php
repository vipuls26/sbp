<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

#[Fillable(['name', 'description', 'pricing', 'duration', 'admin_id'])]
class Plan extends Model
{
    use SoftDeletes;

    // plan created by admin
    public function admin()
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
        $query->whereDoesntHave('subscriptions', function ($query) {
            $query->where('subscriber_id', Auth::user()->id);
        });
    }

    // active plan of user
    #[Scope]
    protected function activeSubscription(Builder $query): void
    {
        $query->whereHas('subscriptions', function ($query) {
            $query->where('end_date', '>', now());
        });
    }
}

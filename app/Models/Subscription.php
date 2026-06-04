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
    // user has multiple subscription
    public function user()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    // subcription belong to plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }


    // logged-in use subscription
    #[Scope]
    protected function activeSubscription(Builder $query): void
    {
        $query->whereHas('user', function ($query) {
            $query->where('subscriber_id', Auth::user()->id);
        });
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'subscriber_id',
    'plan_id',
    'stripe_session_id',
    'stripe_payment_intent_id',
    'stripe_customer_id',
    'amount',
    'status',
    'paid_at',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    // payment belongs to the user who made it
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    // payment belongs to the selected plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

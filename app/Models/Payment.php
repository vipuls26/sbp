<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['subscriber_id', 'plan_id', 'razor_order_id', 'razor_payment_id', 'razor_signature', 'amount', 'status', 'paid_at'])]
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

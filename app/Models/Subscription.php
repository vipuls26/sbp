<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // user has multiple subscription
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // subcription belong to plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name','description','pricing','duration','admin_id'])]
class Plan extends Model
{
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
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'email', 'password', 'role_id', 'stripe_customer_id'])]
#[Hidden(['password', 'remember_token'])]
// #[ScopedBy([NotAdminUser::class])]
class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes;
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // admin can create multiple plan
    public function plans()
    {
        return $this->hasMany(Plan::class, 'admin_id');
    }

    // user has one active subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'subscriber_id');
    }

    // only user with user role
    #[Scope]
    protected function userOnly(Builder $query): void
    {
        $query->whereHas('role', function ($query) {
            $query->where('name', 'user');
        });
    }

}

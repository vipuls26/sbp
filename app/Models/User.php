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
use Laravel\Cashier\Billable;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
// #[ScopedBy([NotAdminUser::class])]
class User extends Authenticatable
{

    use Billable, HasFactory, Notifiable, SoftDeletes;
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
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

    // only user with user role
    #[Scope]
    protected function userOnly(Builder $query): void
    {
        $query->whereHas('role', function ($query) {
            $query->where('name', 'user');
        });
    }

    // feature
    public function hasFeature(string $feature): bool
    {
        $subscription = $this->subscriptions()
            ->with('plan')
            ->latest()
            ->first();

        if (! $subscription || ! $subscription->plan) {
            return false;
        }

        return $subscription->plan->features[$feature] ?? false;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->exists();
    }

    public function projectLimit(): int
    {
        $subscription = $this->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $subscription || ! $subscription->plan) {
            return 0;
        }

        return match (strtolower($subscription->plan->name)) {
            'hobby' => 5,
            'basic' => 20,
            default => PHP_INT_MAX,
        };
    }
}

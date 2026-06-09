<?php

namespace App\Repositories\Auth;

use App\Interfaces\Auth\UserRepositoryInterface;
use App\Models\User;
class UserRepository implements UserRepositoryInterface
{
    // create user in db
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => (int) $data['role'],
        ]);

        return $user;
    }

    // find user if exist in db
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    // save the Stripe customer id for future checkout sessions
    public function updateStripeCustomerId(int $userId, string $stripeCustomerId)
    {
        return User::whereKey($userId)->update([
            'stripe_customer_id' => $stripeCustomerId,
        ]);
    }
}

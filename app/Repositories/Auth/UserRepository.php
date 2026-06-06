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
}

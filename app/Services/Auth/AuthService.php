<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    // create a new user account
    public function register(array $data)
    {
        return $this->userRepository->create($data);
    }

    // authenticate an existing user
    public function authenticate(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (! $user) {
            return false;
        }

        return Auth::attempt($data);
    }

    // log out the current user
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}

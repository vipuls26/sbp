<?php


namespace App\Services\Auth;

use App\Interfaces\Auth\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    // register service
    public function register(array $data)
    {
        return $this->userRepository->create($data);
    }

    // login service
    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (! $user) {
            return false;
        }

        return Auth::attempt($data);
    }

    // logout service
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}

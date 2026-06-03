<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    // show register form
    public function showRegisterForm()
    {
        // get role with there id
        $roles = Role::get(['id', 'name']);
        return view('auth.register', compact('roles'));
    }

    public function register(RegisterRequest $request)
    {
        // validate input data + call auth service for register
        $this->authService->register($request->validated());
        return redirect()->route('auth.login')->with('success', 'Registration complete successfully');
    }
    
    // show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        // validate input data + call auth service for login
        $success = $this->authService->login($request->validated());

        // if email not found then redirect to with msg
        if (!$success) {
            return back()->withErrors([
                'email' => 'Credentials provided do not match',
            ])->onlyInput('email');
        }

        return redirect()->route('user.dashboard');
    }

    // logout
    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('auth.login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // show register form
    public function showRegisterForm()
    {
        $roles = Role::get(['id', 'name']);
        return view('auth.register', compact('roles'));
    }

    public function register(RegisterRequest $request)
    {
        // validate input data
        $request->validated();

        // add user in database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role
        ]);

        return redirect()->route('auth.login')->with('success', 'Registration complete successfully');
    }


    // show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            $role = $user->role->name;

            if ($role === 'admin') {
                return redirect()->intended('admin/dashboard')->with('success', 'Welcome to dashboard');
            }

            return redirect()->intended('/user/dashboard')->with('success', 'Welcome to dashboard');
        }

        // if email not found then redirect to with msg
        return back()->withErrors([
            'email' => 'Credentials provided do not match',
        ])->onlyInput('email');
    }

    // logout
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}

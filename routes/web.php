<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login-user', [AuthController::class, 'login'])->name('auth.loginUser');

// register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register-user', [AuthController::class, 'register'])->name('auth.create');

// logout
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');


// user route
Route::prefix('/user')->middleware('role:user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});


Route::prefix('/admin')->middleware('role:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

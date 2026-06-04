<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// auth routes

// login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login-user', [AuthController::class, 'login'])->name('auth.loginUser');

// register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register-user', [AuthController::class, 'register'])->name('auth.create');

// logout
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');



// user routes
Route::prefix('/user')->middleware('role:user')->group(function () {
    // dashboard
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');

    // pricing route
    Route::get('/plans', [UserController::class, 'show'])->name('user.plans');
});

// admin routes
Route::prefix('/admin')->middleware('role:admin')->group(function () {
    // dashboard route
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // block user
    Route::post('/{user}/block-user', [AdminController::class, 'block'])->name('admin.block');

    // subscriber
    Route::get('/subscriber', [AdminController::class, 'subscriber'])->name('admin.subscriber');
});


// plan routes
Route::prefix('/plan')->middleware('role:admin')->group(function () {
    // all plans
    Route::get('/all-plans', [PlanController::class, 'index'])->name('plans.index');

    // add plan
    Route::get('/add-plan', [PlanController::class, 'add'])->name('plan.add');
    Route::post('/add-plan', [PlanController::class, 'store'])->name('plan.store');

    // update plan
    Route::get('/{plan}/plan', [PlanController::class, 'edit'])->name('plan.edit');
    Route::post('/{plan}/update-plan', [PlanController::class, 'update'])->name('plan.update');

    Route::post('/{plan}/delete', [PlanController::class, 'destroy'])->name('plan.destroy');
});


// subscription route
Route::prefix('/subscription')->middleware('role:user')->group(function () {
    // add subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/{plan}/store-subscription', [SubscriptionController::class, 'storeSubscription'])->name('subscription.storeSubscription');

    // update subscription
    Route::put('/{plan}/update-subscription', [SubscriptionController::class, 'update'])->name('subscription.update');

    // cancel subscription
    Route::post('/cancel-subscription', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

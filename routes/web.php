<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// auth routes
// login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login-user', [AuthController::class, 'authenticate'])->name('auth.authenticate');

// register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register-user', [AuthController::class, 'store'])->name('auth.store');

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

    // all users
    Route::get('/all-users', [AdminController::class, 'users'])->name('admin.users.index');
    // block user
    Route::post('/{user}/block-user', [AdminController::class, 'blockUser'])->name('admin.users.block');
    // unblock user
    Route::post('/{user}/unblock-user', [AdminController::class, 'restoreUser'])
        ->withTrashed()
        ->name('admin.users.restore');
    // subscriber
    Route::get('/subscriber', [AdminController::class, 'subscribers'])->name('admin.subscribers.index');
});


// plan routes
Route::prefix('/plan')->middleware('role:admin')->group(function () {
    // all plans
    Route::get('/all-plans', [PlanController::class, 'index'])->name('plans.index');
    // add plan
    Route::get('/add-plan', [PlanController::class, 'create'])->name('plans.create');
    Route::post('/add-plan', [PlanController::class, 'store'])->name('plans.store');
    // update plan
    Route::get('/{plan}/plan', [PlanController::class, 'edit'])->name('plans.edit');
    Route::post('/{plan}/update-plan', [PlanController::class, 'update'])->name('plans.update');
    // deactive plan
    Route::post('/{plan}/delete', [PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
});


// subscription route
Route::prefix('/subscription')->middleware('role:user')->group(function () {
    // add subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/{plan}/store-subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    // update subscription
    Route::put('/{plan}/update-subscription', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    // cancel subscription
    Route::post('/cancel-subscription', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});


Route::prefix('/payment')->middleware('role:user')->group(function () {
    // show payment page
    Route::get('/payment/{plan}', [PaymentController::class, 'create'])->name('payments.create');
    // add payment
    Route::post('/make-payment/{plan}', [PaymentController::class, 'store'])->name('payments.store');
});

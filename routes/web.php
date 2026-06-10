<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeamController;
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

    Route::get('/payment-history', [PaymentController::class, 'paymentHistory'])
        ->name('user.payment-history');

    Route::get('/invoice/{id}', [SubscriptionController::class, 'downloadInvoice']);
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
    // subscription details — must be before {plan} wildcard routes
    Route::get('/details', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    // cancel subscription
    Route::post('/cancel-subscription', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    // download invoice
    Route::get('/payment/{payment}/download-invoice', [SubscriptionController::class, 'downloadInvoice'])->name('subscriptions.invoice.download');
    Route::post('/{plan}/store-subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/{plan}/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::get('/{plan}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    // update subscription
    Route::put('/{plan}/update-subscription', [SubscriptionController::class, 'update'])->name('subscriptions.update');
});



// feature-gated routes
Route::prefix('/user')->middleware(['role:user', 'feature:project'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::prefix('/user')->middleware(['role:user', 'feature:team_management'])->group(function () {
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/members', [TeamController::class, 'addMember'])->name('team.members.add');
    Route::delete('/team/members/{member}', [TeamController::class, 'removeMember'])->name('team.members.remove');
});

Route::prefix('/user')->middleware(['role:user', 'feature:analytics'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});

<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    // show pricing page
    public function show()
    {
        // $plans = Plan::notSubscribed()->where('is_active', 1)->get();
        $plans = Plan::where('is_active', 1)->get();
        $subscription = Subscription::activeSubscription()->first();

        // dd($plans,$subscription);
        return view('user.plans', compact('plans','subscription'));
    }

}

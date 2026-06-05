<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionNotification;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    // show pricing page
    public function show()
    {
        // Only show plans that are active for users.
        $plans = Plan::where('is_active', 'true')->get();
        $subscription = Subscription::activeSubscription()->first();

        return view('user.plans', compact('plans','subscription'));
    }


    // send email for subscription expire
    public function expireSubscription()
    {
        $notification = Auth::user()->subscriptions->expireSubscription(3)->first();
        
    }

}

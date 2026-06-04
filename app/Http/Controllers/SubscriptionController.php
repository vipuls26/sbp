<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    // show subscription
    public function index()
    {
        $subscriptions = Subscription::userSubscription()->get();
        return view('subscription.index', compact('subscriptions'));
    }

    // add subscription
    public function storeSubscription(Plan $plan)
    {
        $this->subscriptionService->create($plan);
        return redirect()->route('user.dashboard')->with('success', 'Plan Subscribed Successfully');
    }

    // update subscription
    public function update(int $id, Subscription $subscription)
    {
        $this->subscriptionService->update($id, $subscription);
        return redirect()->route('user.dashboard')->with('success', 'Subscription Updated Successfully');
    }
}

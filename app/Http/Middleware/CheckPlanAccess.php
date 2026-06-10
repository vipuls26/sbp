<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! Auth::check()) {
            return redirect()->route('auth.login');
        }

        $knownFeatures = ['project', 'team_management', 'analytics'];

        if (! in_array($feature, $knownFeatures, true)) {
            return redirect()->route('user.plans')
                ->with('error', 'Invalid feature access requested.');
        }

        $subscription = Auth::user()
            ->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $subscription) {
            return redirect()->route('user.plans')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        $features = $subscription->plan?->features ?? [];

        if (empty($features[$feature])) {
            return redirect()->route('user.plans')
                ->with('error', 'Your current plan does not include this feature. Please upgrade.');
        }

        return $next($request);
    }
}

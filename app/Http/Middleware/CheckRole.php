<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next, string $role): Response
    {
        // check if user is login or not
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        // fetch login user detail
        $user = Auth::user();

        // fetch role
        $roleName = $user->role->name;

        if ($roleName != $role) {
            abort(403, 'You are not authorized for this action');
        }

        return $next($request);
    }
}

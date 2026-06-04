<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    // show pricing page
    public function show()
    {
        $plans = Plan::notSubscribed()->get();
        return view('user.plans', compact('plans'));
    }

}

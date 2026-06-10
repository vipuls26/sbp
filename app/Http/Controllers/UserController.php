<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    // dashboard
    public function index()
    {
        return view('user.dashboard');
    }

    // show pricing page
    public function show()
    {
        $dashboard = $this->userService->dashboard();
        return view('user.plans', $dashboard);
    }

    public function myPlan()
    {
        $myPlan = $this->userService->myPlan();
        return view('user.my-plan', $myPlan);
    }
}

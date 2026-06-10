<?php

namespace App\Http\Controllers;

use App\Services\Analytics\AnalyticsService;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService) {}

    public function index()
    {
        $stats = $this->analyticsService->stats(Auth::id());

        return view('user.analytics.index', compact('stats'));
    }
}

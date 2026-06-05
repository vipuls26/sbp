<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\PlanAdd;
use App\Http\Requests\Plan\PlanUpdate;
use App\Models\Plan;
use App\Services\Plan\PlanService;

class PlanController extends Controller
{

    public function __construct(private PlanService $planService) {}

    public function index()
    {
        $plans = Plan::withCount('subscriptions')->get();
        return view('plan.index', compact('plans'));
    }

    public function add()
    {
        return view('plan.add');
    }

    public function store(PlanAdd $request)
    {
        $this->planService->add($request->validated());
        return redirect()->route('plans.index')->with('success', 'Plan added successfully');
    }

    public function edit(Plan $plan)
    {
        return view('plan.update', compact('plan'));
    }

    public function update(PlanUpdate $request, Plan $plan)
    {
        $this->planService->update($plan->id ,$request->validated());
        return redirect()->route('plans.index')->with('success', 'Plan Updated successfully');
    }

    public function destroy(Plan $plan)
    {
        $result = $this->planService->toggleStatus($plan->id);

        if (! $result['success']) {
            return redirect()->route('plans.index')->with('error', $result['message']);
        }

        return redirect()->route('plans.index')->with('success', $result['message']);
    }
}

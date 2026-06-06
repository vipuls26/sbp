<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\PlanAdd;
use App\Http\Requests\Plan\PlanUpdate;
use App\Models\Plan;
use App\Services\Plan\PlanService;

class PlanController extends Controller
{

    public function __construct(private PlanService $planService) {}

    // show plans to admin
    public function index()
    {
        $plans = $this->planService->dashboard();
        return view('plan.index', $plans);
    }

    // show add form
    public function create()
    {
        return view('plan.add');
    }

    // store a new plan
    public function store(PlanAdd $request)
    {
        $this->planService->create($request->validated());
        return redirect()->route('plans.index')->with('success', 'Plan added successfully');
    }

    // show edit form
    public function edit(Plan $plan)
    {
        return view('plan.update', compact('plan'));
    }

    // update plan
    public function update(PlanUpdate $request, Plan $plan)
    {
        $this->planService->update($plan->id, $request->validated());
        return redirect()->route('plans.index')->with('success', 'Plan Updated successfully');
    }

    // toggle active/deactive plan
    public function toggleStatus(Plan $plan)
    {
        $result = $this->planService->toggleStatus($plan->id);

        if (! $result['success']) {
            return redirect()->route('plans.index')->with('error', $result['message']);
        }

        return redirect()->route('plans.index')->with('success', $result['message']);
    }
}

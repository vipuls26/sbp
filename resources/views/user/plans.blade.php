<x-layout title="Pricing">

    <x-header />

    <div class="min-h-screen bg-slate-950 text-white pb-20">
        <div class="max-w-7xl mx-auto px-6 mb-16">
            <h2 class="text-2xl font-bold mb-8 text-slate-200">Monthly Plans</h2>

            @if ($monthlyPlans->isNotEmpty())
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($monthlyPlans as $plan)
                        @include('components.plan-card', [
                            'plan' => $plan,
                            'subscription' => $subscription,
                        ])
                    @endforeach
                </div>
            @else
                <p class="text-slate-500">No monthly plans available.</p>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-6 mb-16">
            <h2 class="text-2xl font-bold mb-8 text-slate-200">Annual Plans</h2>

            @if ($annualPlans->isNotEmpty())
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach ($annualPlans as $plan)
                        @include('components.plan-card', [
                            'plan' => $plan,
                            'subscription' => $subscription,
                        ])
                    @endforeach
                </div>
            @else
                <p class="text-slate-500">No annual plans available.</p>
            @endif
        </div>

    </div>

</x-layout>

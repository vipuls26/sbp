<div
    class="relative flex flex-col rounded-3xl border p-8 transition-all duration-300 hover:-translate-y-1
    {{ $subscription && $subscription->plan_id == $plan->id
        ? 'border-emerald-500 bg-emerald-500/5 ring-1 ring-emerald-500'
        : 'border-slate-800 bg-slate-900 hover:border-slate-700' }}">


    @if ($subscription && $subscription->plan_id == $plan->id)
        <span class="absolute top-4 right-4 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">
            Active
        </span>
    @endif


    <div class="mt-6">
        <h3 class="text-2xl font-bold text-white">
            {{ $plan->name }}
        </h3>

        <p class="mt-2 text-sm text-slate-400">
            {{ $plan->description }}
        </p>
    </div>


    <div class="mt-8">
        <span class="text-5xl font-bold text-emerald-400">
            ₹{{ number_format($plan->pricing, 0) }}
        </span>

        <span class="text-slate-400">
            /{{ strtolower($plan->duration) }}
        </span>
    </div>

    @php
        $featureLabels = [
            'project' => 'Project Access',
            'team_management' => 'Team Management',
            'analytics' => 'Analytics',
        ];
    @endphp

    <ul class="mt-8 space-y-3 text-sm text-slate-300 flex-1">
        @foreach ($featureLabels as $key => $label)
            @if (!empty($plan->features[$key]))
                <li class="flex items-center gap-2">
                    <span>✓</span> {{ $label }}
                </li>
            @endif
        @endforeach
    </ul>


    <div class="mt-10">

        @if (!$subscription)

            <form action="{{ route('subscriptions.store', $plan) }}" method="POST">
                @csrf

                <button
                    class="w-full rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-500">
                    Choose Plan
                </button>
            </form>
        @elseif ($subscription->plan_id == $plan->id)
            <a href="{{ route('subscriptions.show') }}"
                class="block w-full rounded-xl bg-emerald-600 px-6 py-3 text-center font-semibold text-white transition hover:bg-emerald-500">
                Current Plan
            </a>
        @else
            <form action="{{ route('subscriptions.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($plan->pricing > $subscription->plan->pricing)
                    <button
                        class="w-full rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-500">
                        Upgrade Plan
                    </button>
                @else
                    <button
                        class="w-full rounded-xl bg-slate-600 px-6 py-3 font-semibold text-white transition hover:bg-slate-500">
                        Downgrade Plan
                    </button>
                @endif
            </form>

        @endif

    </div>
</div>

<x-layout title="Subscription Details">

    <x-header />

    <div class="min-h-screen bg-slate-950 text-white pb-20">
        <div class="max-w-2xl mx-auto px-6 py-12">

            <div class="mb-8">
                <a href="{{ route('user.plans') }}" class="text-sm text-slate-400 hover:text-white transition">← Back to Plans</a>
            </div>

            @if ($subscription)

                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-8">

                    <h1 class="text-2xl font-bold mb-8">Subscription Details</h1>

                    <div class="grid sm:grid-cols-2 gap-6 text-sm">

                        <div>
                            <p class="text-slate-400 mb-1">Current Plan</p>
                            <p class="font-semibold text-white">
                                {{ $subscription->plan->name }}
                                <span class="text-slate-400 font-normal">({{ $subscription->plan->duration }})</span>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Status</p>
                            @if ($subscription->cancel_at_period_end)
                                <p class="font-semibold text-yellow-400">Canceling at period end</p>
                            @else
                                <p class="font-semibold text-emerald-400">Active</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Expiry Date</p>
                            <p class="font-semibold text-white">
                                {{ $subscription->end_date ? $subscription->end_date->format('d M Y') : '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Amount</p>
                            <p class="font-semibold text-emerald-400">₹{{ number_format($subscription->plan->pricing, 0) }}</p>
                        </div>

                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-800">
                        @if (!$subscription->cancel_at_period_end)
                            <form action="{{ route('subscriptions.destroy') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-500">
                                    Cancel Subscription
                                </button>
                            </form>
                        @else
                            <div class="rounded-xl border border-yellow-500/20 bg-yellow-500/10 px-4 py-3 text-sm text-yellow-300">
                                Your subscription will remain active until
                                <span class="font-semibold">
                                    {{ $subscription->end_date ? $subscription->end_date->format('d M Y') : '—' }}
                                </span>.
                            </div>
                        @endif
                    </div>

                </div>

            @else

                <div class="rounded-3xl border border-white/10 bg-white/5 p-10 text-center">
                    <p class="text-lg font-semibold text-white">No active subscription</p>
                    <p class="mt-2 text-sm text-slate-400">Subscribe to a plan to see your details here.</p>
                    <a href="{{ route('user.plans') }}"
                        class="mt-6 inline-block rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">
                        View Plans
                    </a>
                </div>

            @endif

        </div>
    </div>

</x-layout>

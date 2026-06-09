<x-layout title="Pricing">

    <x-header />

    <div class="bg-slate-950 via-black to-slate-900 text-white min-h-screen py-16 px-4">

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-16">

            @if ($plans->isNotEmpty())
                @foreach ($plans as $plan)
                    <div
                        class="group bg-neutral-900/90 border border-white/10 rounded-3xl p-8 shadow-xl shadow-black/20 flex flex-col justify-between transition duration-200 hover:-translate-y-1 hover:border-white/20 hover:bg-neutral-900 {{ $subscription && $subscription->plan_id == $plan->id ? 'ring-1 ring-emerald-500/80' : '' }} ">
                        <div>
                            <div class="mb-6 flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                                    <p class="text-slate-400 text-sm leading-6">{{ $plan->description }}</p>
                                </div>

                                <span
                                    class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium uppercase tracking-wide text-slate-200">
                                    {{ $plan->duration }}
                                </span>
                            </div>

                            <div class="flex items-end gap-2 mb-6">
                                <span class="text-4xl font-extrabold tracking-tight text-emerald-400">₹{{ $plan->pricing }}</span>
                                <span class="pb-1 text-sm font-medium text-slate-400">/ {{ $plan->duration }}</span>
                            </div>
                        </div>

                        @if (!$subscription)
                            <form action="{{ route('subscriptions.store', $plan) }}" method="POST">
                                @csrf
                                <button
                                    class="block w-full mt-6 rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white shadow-lg shadow-indigo-950/30 transition hover:bg-indigo-500 text-center">
                                    Choose Plan
                                </button>
                            </form>
                        @elseif ($plan->id != $subscription->plan_id)
                            <form action="{{ route('subscriptions.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if ($plan->pricing > $subscription->plan->pricing)
                                    <button
                                        class="w-full mt-6 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500">
                                        Upgrade plan
                                    </button>
                                @else
                                    <button
                                        class="w-full mt-6 rounded-xl bg-orange-600 px-6 py-3 font-semibold text-white shadow-lg shadow-orange-950/30 transition hover:bg-orange-500">
                                        Downgrade plan
                                    </button>
                                @endif

                            </form>
                        @else
                            <div class="gap-2">
                                <button
                                    class="w-full mt-6 rounded-xl bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-emerald-950/30">
                                    Current Plan
                                </button>

                                <form action="{{ route('subscriptions.destroy') }}" method="POST">
                                    @csrf
                                    <button
                                        class="w-full mt-4 rounded-xl border border-red-500/30 bg-red-500/10 px-6 py-3 font-semibold text-red-200 transition hover:bg-red-500/20">
                                        Cancel Plan
                                    </button>

                                </form>
                            </div>
                        @endif

                    </div>
                @endforeach
            @else
                <div class="col-span-full py-20 text-center">
                    <div class="mx-auto max-w-md rounded-3xl border border-white/10 bg-white/5 p-10">
                        <p class="text-xl font-semibold text-white">No plan added by admin</p>
                        <p class="mt-2 text-sm text-slate-400">Once plans are added, they will appear here.</p>
                    </div>
                </div>
            @endif
        </div>

        @if ($latestPayment)
            <div class="max-w-6xl mx-auto pb-16">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl shadow-black/20">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide text-slate-400">Latest Invoice</p>
                            <h3 class="mt-1 text-xl font-bold text-white">
                                ₹{{ $latestPayment->amount }} - {{ $latestPayment->plan?->name ?? 'Subscription' }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-400">
                                Paid at {{ $latestPayment->paid_at?->format('d M Y, h:i A') ?? 'N/A' }}
                            </p>
                        </div>

                        @if ($latestPayment->stripe_invoice_id)
                            <a href="{{ route('subscriptions.invoice.download', $latestPayment) }}"
                                class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                                Download Invoice
                            </a>
                        @else
                            <span class="inline-flex items-center rounded-xl border border-white/10 px-4 py-3 text-sm text-slate-300">
                                Invoice will appear after Stripe finalizes it
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>

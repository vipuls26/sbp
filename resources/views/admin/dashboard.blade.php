<x-layout title="Admin Dashboard">

    <x-header />

    <div class="min-h-full from-slate-50 via-white to-slate-100 px-6 py-12 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Total Users</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $users }}</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Blocked Users</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $blockedUsers }}</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Total Plans</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $plans }}</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Total Subscriptions</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $subscriptions->count() }}</p>
                </div>

                <div class="rounded-3xl bg-slate-900 p-6 shadow-sm ring-1 ring-slate-800">
                    <p class="text-sm font-medium text-slate-300">Total Earning</p>
                    <p class="mt-3 text-3xl font-bold text-white">₹{{ $totalEarning }}</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium uppercase tracking-wide text-slate-500">Overview</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Transaction list</h2>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr class="text-left text-sm font-medium text-slate-500">
                                <th class="pb-3 pr-4">User</th>
                                <th class="pb-3 pr-4">Plan</th>
                                <th class="pb-3 pr-4">Amount</th>
                                <th class="pb-3 pr-4">Status</th>
                                <th class="pb-3 pr-4">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($latestPayments as $payment)
                                <tr class="text-sm">
                                    <td class="py-4 pr-4 font-medium text-slate-900">
                                        {{ $payment->subscriber?->name ?? 'Unknown' }}
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">
                                        {{ $payment->plan?->name ?? 'Unknown' }}
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">
                                        ₹{{ $payment->amount }}
                                    </td>
                                    <td class="py-4 pr-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $payment->status === 'success' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $payment->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $payment->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-4 text-slate-500">
                                        {{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-500">
                                        No payment records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>

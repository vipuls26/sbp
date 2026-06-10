<x-layout title="Payment History">

    <x-header />

    <div class="bg-slate-950 text-white min-h-screen py-16 px-4">
        <div class="max-w-5xl mx-auto">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white">Payment History</h1>
                <p class="mt-1 text-sm text-slate-400">All your past transactions and invoices.</p>
            </div>

            <div class="rounded-3xl border border-white/10 bg-neutral-900/90 shadow-xl shadow-black/20 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead>
                            <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Plan</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Currency</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($payments as $payment)
                                <tr class="text-sm transition hover:bg-white/5">
                                    <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-white">
                                        {{ $payment->plan?->name ?? '—' }}
                                        @if ($payment->plan?->duration)
                                            <span
                                                class="ml-1 text-xs text-slate-500">({{ $payment->plan->duration }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-emerald-400 font-semibold">
                                        ₹{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-400 uppercase">
                                        {{ $payment->currency ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $payment->status === 'success' ? 'bg-emerald-500/15 text-emerald-400' : '' }}
                                            {{ $payment->status === 'pending' ? 'bg-amber-500/15 text-amber-400' : '' }}
                                            {{ $payment->status === 'failed' ? 'bg-red-500/15 text-red-400' : '' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($payment->stripe_invoice_id)
                                            <a href="{{ route('subscriptions.invoice.download', $payment) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1 rounded-lg border border-indigo-500/30 bg-indigo-500/10 px-3 py-1.5 text-xs font-medium text-indigo-300 transition hover:bg-indigo-500/20">
                                                View Invoice
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-600">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <p class="text-slate-400 font-medium">No payment records found.</p>
                                        <p class="mt-1 text-sm text-slate-600">Your transactions will appear here after
                                            your first purchase.</p>
                                        <a href="{{ route('user.plans') }}"
                                            class="mt-6 inline-block rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">
                                            View Plans
                                        </a>
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

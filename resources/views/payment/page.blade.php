<x-layout title="Payment">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-5xl">
            <div class="mb-8">
                <h1 class="mt-2 text-3xl font-bold md:text-4xl">Complete your payment</h1>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="rounded-2xl border border-neutral-800 bg-neutral-900 p-6 shadow-xl">
                    <div class="mb-6 flex items-center justify-between border-b border-neutral-800 pb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Selected Plan</h2>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between rounded-xl bg-black/40 px-4 py-3">
                            <span class="text-sm text-gray-400">Plan name</span>
                            <span class="font-medium text-white">{{ $plan->name }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-black/40 px-4 py-3">
                            <span class="text-sm text-gray-400">Duration</span>
                            <span class="font-medium text-white">{{ ucfirst($plan->duration) }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-black/40 px-4 py-3">
                            <span class="text-sm text-gray-400">Amount</span>
                            <span class="text-lg font-bold text-emerald-400">₹{{ $plan->pricing }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-800 bg-neutral-900 p-6 shadow-xl">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-white">Pay now</h2>
                    </div>

                    <form id="razorpay-form" method="POST" action="{{ route('payments.store', $plan) }}">
                        @csrf
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id"
                            value="{{ $orderId }}">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <button type="button" id="pay-now-btn"
                            class="w-full rounded-xl bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-700">
                            Pay ₹{{ $plan->pricing }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            document.getElementById('pay-now-btn').addEventListener('click', function() {
                if (typeof Razorpay === 'undefined') {
                    alert('Razorpay checkout failed to load. Please check your internet connection.');
                    return;
                }

                const options = {
                    key: @json($razorpayKey),
                    amount: @json($amount),
                    currency: @json($currency),
                    name: @json(config('app.name')),
                    description: @json($plan->name . ' Subscription'),
                    order_id: @json($orderId),
                    handler: function(response) {
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        document.getElementById('razorpay-form').submit();
                    },
                    prefill: {
                        name: @json(auth()->user()->name),
                        email: @json(auth()->user()->email),
                    },
                    theme: {
                        color: '#dc2626'
                    }
                };

                new Razorpay(options).open();
            });
        </script>
    @endpush
</x-layout>

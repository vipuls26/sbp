<x-layout>

    <x-header />

    <div class="min-h-screen bg-gray-100 py-10">
        <div class="max-w-2xl mx-auto">

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">


                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">
                        Payment Details
                    </h2>
                </div>

                <div class="p-6">


                    <div class="border rounded-lg p-4 mb-6 bg-gray-50">
                        <h3 class="text-lg font-semibold mb-2">
                            Selected Plan
                        </h3>

                        @if ($plan)
                            <div class="flex justify-between mb-2">
                                <span>Plan Name</span>
                                <span class="font-medium">{{ $plan->name }}</span>
                            </div>

                            <div class="flex justify-between mb-2">
                                <span>Duration</span>
                                <span class="font-medium">{{ ucfirst($plan->duration) }}</span>
                            </div>

                            <div class="flex justify-between text-lg font-bold text-green-600">
                                <span>Total Amount</span>
                                <span>₹{{ $plan->pricing }}</span>
                            </div>
                        @endif
                    </div>


                    <form id="razorpay-form" method="POST" action="{{ route('payments.store', $plan) }}">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{ $paymentId }}">
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id"
                            value="{{ $orderId }}">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <button type="button" id="pay-now-btn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
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
                        color: '#2563eb'
                    }
                };

                new Razorpay(options).open();
            });
        </script>
    @endpush
</x-layout>

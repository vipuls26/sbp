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


                    <form method="POST" action="{{ route('payments.store', $plan) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="card_holder" class="block text-sm font-medium mb-2">
                                Card Holder Name
                            </label>
                            <input id="card_holder" type="text" name="card_holder" value="{{ old('card_holder') }}"
                                placeholder="John Doe"
                                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('card_holder')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="card_number" class="block text-sm font-medium mb-2">
                                Card Number
                            </label>
                            <input id="card_number" type="text" name="card_number" value="{{ old('card_number') }}"
                                placeholder="4242 4242 4242 4242"
                                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('card_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="expiry" class="block text-sm font-medium mb-2">
                                    Expiry Date
                                </label>
                                <input id="expiry" type="text" name="expiry" value="{{ old('expiry') }}"
                                    placeholder="12/30"
                                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('expiry')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cvv" class="block text-sm font-medium mb-2">
                                    CVV
                                </label>
                                <input id="cvv" type="password" name="cvv" value="{{ old('cvv') }}"
                                    placeholder="123"
                                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cvv')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-yellow-800 mb-2">
                                Razorpay Test Card
                            </h4>

                            <p class="text-sm text-yellow-700">
                                Card Number: 4111 1111 1111 1111
                            </p>
                            <p class="text-sm text-yellow-700">
                                Expiry: Any Future Date
                            </p>
                            <p class="text-sm text-yellow-700">
                                CVV: Any 3 Digits
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                            Pay ₹{{ $plan->pricing }}
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-layout>

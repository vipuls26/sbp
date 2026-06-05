<x-layout title="Pricing">

    <x-header />

    <div class="bg-black text-white min-h-screen py-16 px-4">

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-16">

            @if ($plans->isNotEmpty())
                @foreach ($plans as $plan)
                    <div
                        class="bg-neutral-900 border border-neutral-800 rounded-2xl p-8 shadow-xl flex flex-col justify-between {{ $subscription && $subscription->plan_id == $plan->id ? 'ring-1 ring-green-500' : '' }} ">
                        <div>

                            <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-400 text-sm mb-6">{{ $plan->description }}</p>

                            <div class="flex items-baseline text-emerald-500 mb-6">
                                <span class="text-2xl font-extrabold tracking-tight">₹{{ $plan->pricing }}</span>
                                <span class="text-md font-medium text-gray-500 ml-1">/{{ $plan->duration }}</span>
                            </div>
                        </div>

                        @if (!$subscription)
                            <a href="{{ route('payment.page', $plan) }}"
                                class="block w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md20 text-center">
                                Choose Plan
                            </a>
                        @elseif ($plan->id != $subscription->plan_id)
                            <form action="{{ route('subscription.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if ($plan->pricing > $subscription->plan->pricing)
                                    <button
                                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md">
                                        Upgrade plan
                                    </button>
                                @else
                                    <button
                                        class="w-full mt-6 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md">
                                        Downgrade plan
                                    </button>
                                @endif

                            </form>
                        @else
                            <div class="gap-2">
                                <button
                                    class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md">
                                    Current Plan
                                </button>

                                <form action="{{ route('subscription.cancel') }}" method="POST">
                                    @csrf
                                    <button
                                        class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md">
                                        Cancel Plan
                                    </button>

                                </form>
                            </div>
                        @endif

                    </div>
                @endforeach
            @else
                <div class="col-span-full py-20 text-center">
                    <div class="inline-block p-8 rounded-2xl">
                        <p class="text-xl text-white font-semibold">No plan added by admin</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-footer />

</x-layout>

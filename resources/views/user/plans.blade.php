<x-layout title="Pricing">

    <x-header />

    <div class="bg-black text-white min-h-screen py-16 px-4">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <p class="text-gray-400 text-lg">Choose the perfect plan that fits your needs.</p>
        </div>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if ($plans->isNotEmpty())
                @foreach ($plans as $plan)
                    <div
                        class="bg-neutral-900 border border-neutral-800 rounded-2xl p-8 shadow-xl flex flex-col justify-between hover:border-red-600">
                        <div>

                            <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-400 text-sm mb-6">{{ $plan->description }}</p>

                            <div class="flex items-baseline text-red-600 mb-6">
                                <span class="text-2xl font-extrabold tracking-tight">₹{{ $plan->pricing }}</span>
                                <span class="text-md font-medium text-gray-500 ml-1">/{{ $plan->duration }}</span>
                            </div>
                        </div>

                        <form action="{{ route('Subscription.storeSubscription', $plan) }}" method="POST">
                            <button
                                class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md shadow-red-600/20">
                                Choose Plan
                            </button>
                        </form>
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

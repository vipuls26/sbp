<x-layout title="Subscription Page">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

        @if ($subscriptions->isNotEmpty())
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white border border-gray-200">

                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Plan Name</th>
                            <th class="px-4 py-3 text-left">Plan Description</th>
                            <th class="px-4 py-3 text-left">Start Date</th>
                            <th class="px-4 py-3 text-left">End Date</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-4 py-3 font-medium">
                                    {{ $subscription->plan->name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $subscription->plan->description }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $subscription->start_date }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $subscription->end_date }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="gap-2">
                                        @if ($subscription->end_date < now())
                                            <form action="#" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Delete this plan?')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                    Upgrade
                                                </button>
                                            </form>
                                        @else
                                            <form action="#" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Delete this plan?')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                    Renew
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @else
            <div class="flex justify-center p-10">
                <p> No Subscription found. </p>
            </div>
        @endif
        
    </div>

    <x-footer />

</x-layout>

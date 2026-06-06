<x-layout title="Subscribers">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        @if ($subscribers->isNotEmpty())
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Subscriber Name</th>
                            <th class="px-4 py-3 text-left">Subscriber Email</th>
                            <th class="px-4 py-3 text-left">Plan</th>
                            <th class="px-4 py-3 text-left">Description</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-left">Duration</th>
                            <th class="px-4 py-3 text-left">Start Date</th>
                            <th class="px-4 py-3 text-left">End Date</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium"> {{ $subscriber->subscriber?->name  }}
                                </td>
                                <td class="px-4 py-3"> {{ $subscriber->subscriber?->email }} </td>
                                <td class="px-4 py-3"> {{ $subscriber->plan?->name }}  </td>
                                <td class="px-4 py-3"> {{ $subscriber->plan?->description }} </td>
                                <td class="px-4 py-3"> ₹ {{ $subscriber->plan?->pricing  }} </td>
                                <td class="px-4 py-3"> {{ $subscriber->plan?->duration }} </td>
                                <td class="px-4 py-3"> {{ $subscriber->start_date }} </td>
                                <td class="px-4 py-3"> {{ $subscriber->end_date }} </td>


                                @if ($subscriber->end_date > now())
                                    <td class="px-4 py-3">
                                        <span class="bg-green-600 p-2 rounded-2xl text-white"> Active
                                        </span>
                                    </td>
                                @else
                                    <td class="px-4 py-3 font-semibold">
                                        <span class="bg-red-500 p-2 rounded-2xl text-white"> Expired
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 text-center py-4">No subscribers found.</p>
        @endif
    </div>

</x-layout>

<x-layout title="Subscribers">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
     

        @if ($subscribers->isNotEmpty())
            <div class="overflow-x-auto rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
                <table class="min-w-full">
                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-5 py-4 text-left text-sm font-medium">Subscriber Name</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Subscriber Email</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Plan</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Description</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Price</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Duration</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Start Date</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">End Date</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($subscribers as $subscriber)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 font-medium text-slate-900"> {{ $subscriber->subscriber?->name  }}
                                </td>
                                <td class="px-5 py-4 text-slate-600"> {{ $subscriber->subscriber?->email }} </td>
                                <td class="px-5 py-4 text-slate-900"> {{ $subscriber->plan?->name }}  </td>
                                <td class="px-5 py-4 text-slate-600"> {{ $subscriber->plan?->description }} </td>
                                <td class="px-5 py-4 text-slate-600"> ₹ {{ $subscriber->plan?->pricing  }} </td>
                                <td class="px-5 py-4 text-slate-600"> {{ $subscriber->plan?->duration }} </td>
                                <td class="px-5 py-4 text-slate-600"> {{ $subscriber->start_date }} </td>
                                <td class="px-5 py-4 text-slate-600"> {{ $subscriber->end_date }} </td>


                                @if ($subscriber->end_date > now())
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"> Active
                                        </span>
                                    </td>
                                @else
                                    <td class="px-5 py-4 font-semibold">
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"> Expired
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-4 text-slate-500">No subscribers found.</p>
        @endif
    </div>

</x-layout>

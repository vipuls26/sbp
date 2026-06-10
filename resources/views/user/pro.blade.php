<x-layout title="Analytics">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl">

            <div class="mb-10">
                <span class="rounded-full bg-emerald-600/20 px-3 py-1 text-xs font-semibold text-emerald-400 ring-1 ring-emerald-600/30">
                    Analytics
                </span>
                <h1 class="mt-4 text-3xl font-bold text-white">Analytics Overview</h1>
                <p class="mt-2 text-slate-400">Deep insights into your usage and performance.</p>
            </div>

            {{-- stat cards --}}
            <div class="grid gap-6 sm:grid-cols-3 mb-10">
                @foreach ([
                    ['label' => 'Total Users', 'value' => '1,240', 'change' => '+12%'],
                    ['label' => 'Revenue', 'value' => '₹84,500', 'change' => '+8%'],
                    ['label' => 'Active Sessions', 'value' => '342', 'change' => '+3%'],
                ] as $stat)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                        <p class="text-sm text-slate-400">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs text-emerald-400">{{ $stat['change'] }} this month</p>
                    </div>
                @endforeach
            </div>

            {{-- activity table --}}
            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
                <div class="border-b border-slate-800 px-6 py-4">
                    <h2 class="text-sm font-semibold text-white">Recent Activity</h2>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-slate-800 text-slate-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4">Event</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ([
                            ['event' => 'New Subscription', 'user' => 'alice@example.com', 'date' => 'Today, 10:23 AM'],
                            ['event' => 'Plan Upgraded', 'user' => 'bob@example.com', 'date' => 'Yesterday, 4:10 PM'],
                            ['event' => 'Invoice Downloaded', 'user' => 'carol@example.com', 'date' => 'Jul 10, 2:00 PM'],
                        ] as $row)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-medium text-white">{{ $row['event'] }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ $row['user'] }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ $row['date'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-layout>

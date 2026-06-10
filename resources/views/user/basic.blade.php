<x-layout title="Team Management">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl">

            <div class="mb-10">
                <span class="rounded-full bg-blue-600/20 px-3 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-600/30">
                    Team Management
                </span>
                <h1 class="mt-4 text-3xl font-bold text-white">Your Team</h1>
                <p class="mt-2 text-slate-400">Manage team members, roles, and permissions.</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-slate-800 text-slate-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4">Member</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ([
                            ['name' => 'Alice Johnson', 'role' => 'Admin', 'status' => 'Active', 'joined' => 'Jan 2024'],
                            ['name' => 'Bob Smith', 'role' => 'Developer', 'status' => 'Active', 'joined' => 'Mar 2024'],
                            ['name' => 'Carol White', 'role' => 'Designer', 'status' => 'Inactive', 'joined' => 'Jun 2024'],
                        ] as $member)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-6 py-4 font-medium text-white">{{ $member['name'] }}</td>
                                <td class="px-6 py-4 text-slate-400">{{ $member['role'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $member['status'] === 'Active' ? 'bg-emerald-600/20 text-emerald-400' : 'bg-slate-700 text-slate-400' }}">
                                        {{ $member['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ $member['joined'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-layout>

<x-layout title="Team Management">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl space-y-10">

            <div>
                <span class="rounded-full bg-blue-600/20 px-3 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-600/30">
                    Team Management
                </span>
                <h1 class="mt-4 text-3xl font-bold text-white">Your Team</h1>
                <p class="mt-2 text-slate-400">Add and manage your team members.</p>
            </div>

            {{-- add member form --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                <h2 class="mb-4 text-sm font-semibold text-white">Add Member</h2>
                <form action="{{ route('team.members.add') }}" method="POST" class="grid gap-4 sm:grid-cols-3">
                    @csrf
                    <div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name"
                            class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder:text-slate-500 focus:border-blue-500 focus:outline-none" />
                        @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address"
                            class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder:text-slate-500 focus:border-blue-500 focus:outline-none" />
                        @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <select name="role"
                            class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:outline-none">
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="developer" {{ old('role') == 'developer' ? 'selected' : '' }}>Developer</option>
                            <option value="designer" {{ old('role') == 'designer' ? 'selected' : '' }}>Designer</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-3">
                        <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500 transition">
                            Add Member
                        </button>
                    </div>
                </form>
            </div>

            {{-- members list --}}
            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
                @if ($members->isEmpty())
                    <div class="p-12 text-center">
                        <i class="pi pi-users text-4xl text-slate-600"></i>
                        <p class="mt-4 text-slate-400">No team members yet.</p>
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="border-b border-slate-800 text-xs uppercase text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Joined</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach ($members as $member)
                                <tr class="hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 font-medium text-white">{{ $member->name }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ $member->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-blue-600/20 px-2 py-0.5 text-xs text-blue-400">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $member->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('team.members.remove', $member) }}" method="POST"
                                            onsubmit="return confirm('Remove this member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-400 hover:text-red-300 transition">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>

</x-layout>

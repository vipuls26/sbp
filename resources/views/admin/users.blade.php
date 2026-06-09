    <x-layout title="All User">

        <x-header />

        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

            <div class="overflow-x-auto rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
                <table class="min-w-full">

                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-5 py-4 text-left text-sm font-medium">Name</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Email</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Role</th>
                            <th class="px-5 py-4 text-left text-sm font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4 font-medium text-slate-900">
                                    {{ $user->name }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $user->role->name }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex gap-2">

                                        @if ($user->role->name != 'admin' && $user->deleted_at != null)
                                            <form action="{{ route('admin.users.restore', $user) }}" method="POST">
                                                @csrf

                                                <button type="submit"
                                                    onclick="return confirm('Confirm to unblock this user!')"
                                                    class="rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                                                    Unblock
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.block', $user) }}" method="POST">
                                                @csrf

                                                <button type="submit"
                                                    onclick="return confirm('Confirm to block this user')"
                                                    class="rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                                    Block
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-500">
                                    <p>No user found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </x-layout>

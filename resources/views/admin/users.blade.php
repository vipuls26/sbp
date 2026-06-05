<x-layout title="Admin Dashboard">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full bg-white border border-gray-200 ">

                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-4 py-3 font-medium">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->role->name }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="gap-2">

                                    @if ($user->role->name != 'admin' && $user->deleted_at != null)
                                        <form action="{{ route('admin.unblock', $user) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                onclick="return confirm('Confirm to unblock this user!')"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                                Unblock
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.block', $user) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                onclick="return confirm('Confirm to block this user')"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Block
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                <p> No user found.

                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
    <x-footer />
</x-layout>

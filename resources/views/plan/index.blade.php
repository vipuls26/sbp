<x-layout title="All Plans">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="flex justify-end pb-2">
            <a href="{{ route('plan.add') }}" class="bg-slate-900 p-3 rounded-2xl text-white "> Add Plan </a>
        </div>
        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full bg-white border border-gray-200">

                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Plan Name</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Duration</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($plans as $plan)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-4 py-3 font-medium">
                                {{ $plan->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $plan->description }}
                            </td>

                            <td class="px-4 py-3">
                                ₹{{ $plan->pricing }}
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $plan->duration === 'monthly' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $plan->duration }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('plan.edit', $plan) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    <form action="{{ route('plan.destroy', $plan) }}" method="POST">
                                        @csrf

                                        <button type="submit" onclick="return confirm('Delete this plan?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                <p> No plans found. </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
    <x-footer />
</x-layout>

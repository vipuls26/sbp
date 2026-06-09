    <x-layout title="All Plans">

    <x-header />

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="mx-auto mb-8 w-full max-w-7xl">
            <div class="flex items-center justify-end gap-2">


                <a href="{{ route('plans.create') }}"
                    class="inline-flex items-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    Add Plan
                </a>
            </div>
        </div>
        <div class="overflow-x-auto rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <table class="min-w-full">

                <thead class="bg-slate-950 text-white">
                    <tr>
                        <th class="px-5 py-4 text-left text-sm font-medium">Plan Name</th>
                        <th class="px-5 py-4 text-left text-sm font-medium">Description</th>
                        <th class="px-5 py-4 text-left text-sm font-medium">Price</th>
                        <th class="px-5 py-4 text-left text-sm font-medium">Status</th>
                        <th class="px-5 py-4 text-left text-sm font-medium">Duration</th>
                        <th class="px-5 py-4 text-left text-sm font-medium">Stripe Price</th>
                        <th class="px-5 py-4 text-center text-sm font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($plans as $plan)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4 font-medium text-slate-900">
                                {{ $plan->name }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $plan->description }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                ₹ {{ $plan->pricing }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($plan->is_active == 'true')
                                    <span
                                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-semibold
                            {{ $plan->duration === 'monthly' ? 'bg-sky-100 text-sky-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $plan->duration }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $plan->stripe_price_id }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('plans.edit', $plan) }}"
                                        class="rounded-xl bg-amber-500 px-3 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('plans.toggle-status', $plan) }}" method="POST">
                                        @csrf

                                        @if ($plan->is_active == 'true')
                                            <button type="submit"
                                                onclick="return confirm('Want to deactivate this plan?')"
                                                class="rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                                Deactivate Plan
                                            </button>
                                        @else
                                            <button type="submit"
                                                onclick="return confirm('Want to activate this plan?')"
                                                class="rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                                Activate Plan
                                            </button>
                                        @endif
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-500">
                                <p>No plans found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-layout>

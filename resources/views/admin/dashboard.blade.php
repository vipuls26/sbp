<x-layout title="Admin Dashboard">

    <x-header />

    <div class="min-h-full bg-slate-50 px-6 py-12 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">


            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Total Users</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $users }}</p>

                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Blocked Users</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $blockedUsers }}</p>

                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Total Plans</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $plans }}</p>

                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Total Subscriptions</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $subscriptions->count() }}</p>

                </div>

                <div class="rounded-2xl bg-slate-900 p-6 shadow-sm ring-1 ring-slate-800">
                    <p class="text-sm font-medium text-slate-300">Total Earning</p>
                    <p class="mt-3 text-3xl font-bold text-white">₹{{ $totalEarning }}</p>

                </div>
            </div>

        

        </div>
    </div>

    <x-footer />

</x-layout>

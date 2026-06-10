<x-layout title="Projects">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl">

            <div class="mb-10">
                <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-400 ring-1 ring-indigo-600/30">
                    Project Access
                </span>
                <h1 class="mt-4 text-3xl font-bold text-white">Your Projects</h1>
                <p class="mt-2 text-slate-400">Manage and track all your projects in one place.</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (['Website Redesign', 'Mobile App', 'API Integration'] as $project)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-white">{{ $project }}</span>
                            <span class="rounded-full bg-emerald-600/20 px-2 py-0.5 text-xs text-emerald-400">Active</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-400">Last updated 2 days ago</p>
                        <div class="mt-4 h-1.5 w-full rounded-full bg-slate-800">
                            <div class="h-1.5 rounded-full bg-indigo-500" style="width: 60%"></div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">60% complete</p>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</x-layout>

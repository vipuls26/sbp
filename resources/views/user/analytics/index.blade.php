<x-layout title="Analytics">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl space-y-10">

            <div>
                <span class="rounded-full bg-emerald-600/20 px-3 py-1 text-xs font-semibold text-emerald-400 ring-1 ring-emerald-600/30">
                    Analytics
                </span>
                <h1 class="mt-4 text-3xl font-bold text-white">Analytics Overview</h1>
                <p class="mt-2 text-slate-400">Insights into your project activity.</p>
            </div>

            {{-- stat cards --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                    <p class="text-sm text-slate-400">Total Projects</p>
                    <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>

                <div class="rounded-2xl border border-emerald-800/40 bg-emerald-900/10 p-6">
                    <p class="text-sm text-slate-400">Active</p>
                    <p class="mt-2 text-4xl font-bold text-emerald-400">{{ $stats['active'] }}</p>
                </div>

                <div class="rounded-2xl border border-blue-800/40 bg-blue-900/10 p-6">
                    <p class="text-sm text-slate-400">Completed</p>
                    <p class="mt-2 text-4xl font-bold text-blue-400">{{ $stats['completed'] }}</p>
                </div>

                <div class="rounded-2xl border border-yellow-800/40 bg-yellow-900/10 p-6">
                    <p class="text-sm text-slate-400">On Hold</p>
                    <p class="mt-2 text-4xl font-bold text-yellow-400">{{ $stats['on_hold'] }}</p>
                </div>

            </div>

            {{-- completion rate --}}
            @if ($stats['total'] > 0)
                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                    <p class="mb-3 text-sm font-semibold text-white">Completion Rate</p>
                    @php $rate = round(($stats['completed'] / $stats['total']) * 100); @endphp
                    <div class="h-3 w-full rounded-full bg-slate-800">
                        <div class="h-3 rounded-full bg-emerald-500 transition-all" style="width: {{ $rate }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ $rate }}% of projects completed</p>
                </div>
            @endif

        </div>
    </div>

</x-layout>

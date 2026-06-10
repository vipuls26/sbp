<x-layout title="User Dashboard">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-4xl">

            <div class="mb-10">
                <h1 class="text-3xl font-bold text-white">Hello, {{ auth()->user()->name }} 👋</h1>
                <p class="mt-2 text-slate-400">Manage your subscription and explore features available on your plan.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <a href="{{ route('user.plans') }}"
                    class="flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900 p-6 hover:border-indigo-500 transition group">
                    <i class="pi pi-credit-card text-2xl text-indigo-400"></i>
                    <div>
                        <p class="font-semibold text-white group-hover:text-indigo-400 transition">Pricing Plans</p>
                        <p class="text-xs text-slate-500">View and manage your subscription</p>
                    </div>
                </a>

                <a href="{{ route('user.payment-history') }}"
                    class="flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900 p-6 hover:border-blue-500 transition group">
                    <i class="pi pi-history text-2xl text-blue-400"></i>
                    <div>
                        <p class="font-semibold text-white group-hover:text-blue-400 transition">Payment History</p>
                        <p class="text-xs text-slate-500">View past invoices and payments</p>
                    </div>
                </a>

                @php $hasProject = auth()->user()->hasFeature('project'); @endphp
                <a href="{{ $hasProject ? route('projects.index') : route('user.plans') }}"
                    class="flex items-center gap-4 rounded-2xl border p-6 transition group
                        {{ $hasProject ? 'border-slate-800 bg-slate-900 hover:border-indigo-500' : 'border-slate-800 bg-slate-900/40 opacity-60 cursor-not-allowed' }}">
                    <i class="pi pi-folder text-2xl {{ $hasProject ? 'text-indigo-400' : 'text-slate-600' }}"></i>
                    <div>
                        <p class="font-semibold {{ $hasProject ? 'text-white group-hover:text-indigo-400' : 'text-slate-500' }} transition flex items-center gap-2">
                            Projects @if (!$hasProject) <i class="pi pi-lock text-xs"></i> @endif
                        </p>
                        <p class="text-xs text-slate-500">{{ $hasProject ? 'Manage your projects' : 'Upgrade to access' }}</p>
                    </div>
                </a>

                @php $hasTeam = auth()->user()->hasFeature('team_management'); @endphp
                <a href="{{ $hasTeam ? route('team.index') : route('user.plans') }}"
                    class="flex items-center gap-4 rounded-2xl border p-6 transition group
                        {{ $hasTeam ? 'border-slate-800 bg-slate-900 hover:border-blue-500' : 'border-slate-800 bg-slate-900/40 opacity-60 cursor-not-allowed' }}">
                    <i class="pi pi-users text-2xl {{ $hasTeam ? 'text-blue-400' : 'text-slate-600' }}"></i>
                    <div>
                        <p class="font-semibold {{ $hasTeam ? 'text-white group-hover:text-blue-400' : 'text-slate-500' }} transition flex items-center gap-2">
                            Team Management @if (!$hasTeam) <i class="pi pi-lock text-xs"></i> @endif
                        </p>
                        <p class="text-xs text-slate-500">{{ $hasTeam ? 'Manage your team' : 'Upgrade to access' }}</p>
                    </div>
                </a>

                @php $hasAnalytics = auth()->user()->hasFeature('analytics'); @endphp
                <a href="{{ $hasAnalytics ? route('analytics.index') : route('user.plans') }}"
                    class="flex items-center gap-4 rounded-2xl border p-6 transition group
                        {{ $hasAnalytics ? 'border-slate-800 bg-slate-900 hover:border-emerald-500' : 'border-slate-800 bg-slate-900/40 opacity-60 cursor-not-allowed' }}">
                    <i class="pi pi-chart-bar text-2xl {{ $hasAnalytics ? 'text-emerald-400' : 'text-slate-600' }}"></i>
                    <div>
                        <p class="font-semibold {{ $hasAnalytics ? 'text-white group-hover:text-emerald-400' : 'text-slate-500' }} transition flex items-center gap-2">
                            Analytics @if (!$hasAnalytics) <i class="pi pi-lock text-xs"></i> @endif
                        </p>
                        <p class="text-xs text-slate-500">{{ $hasAnalytics ? 'View analytics overview' : 'Upgrade to access' }}</p>
                    </div>
                </a>

            </div>
        </div>
    </div>

</x-layout>

<x-layout title="Projects">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-5xl">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Projects</h1>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $count }} / {{ $limit === PHP_INT_MAX ? 'Unlimited' : $limit }} projects used
                    </p>
                </div>
                @if ($limit === PHP_INT_MAX || $count < $limit)
                    <a href="{{ route('projects.create') }}"
                        class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                        + New Project
                    </a>
                @else
                    <span class="rounded-xl bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-400 cursor-not-allowed"
                        title="Project limit reached. Upgrade your plan.">
                        + New Project
                    </span>
                @endif
            </div>

            @if ($projects->isEmpty())
                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-12 text-center">
                    <i class="pi pi-folder-open text-4xl text-slate-600"></i>
                    <p class="mt-4 text-slate-400">No projects yet. Create your first one.</p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <div class="flex flex-col rounded-2xl border border-slate-800 bg-slate-900 p-6 hover:border-slate-700 transition">
                            <div class="flex items-start justify-between">
                                <span class="font-semibold text-white">{{ $project->name }}</span>
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                    {{ $project->status === 'active' ? 'bg-emerald-600/20 text-emerald-400' :
                                       ($project->status === 'completed' ? 'bg-blue-600/20 text-blue-400' : 'bg-yellow-600/20 text-yellow-400') }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>

                            @if ($project->description)
                                <p class="mt-2 text-sm text-slate-400 flex-1">{{ $project->description }}</p>
                            @endif

                            <div class="mt-4 h-1.5 w-full rounded-full bg-slate-800">
                                <div class="h-1.5 rounded-full bg-indigo-500 transition-all"
                                    style="width: {{ $project->progress }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $project->progress }}% complete</p>

                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('projects.edit', $project) }}"
                                    class="flex-1 rounded-lg border border-slate-700 py-1.5 text-center text-xs text-slate-300 hover:border-indigo-500 hover:text-indigo-400 transition">
                                    Edit
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                    onsubmit="return confirm('Delete this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg border border-slate-700 px-3 py-1.5 text-xs text-slate-300 hover:border-red-500 hover:text-red-400 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</x-layout>

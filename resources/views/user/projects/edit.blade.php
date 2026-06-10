<x-layout title="Edit Project">

    <x-header />

    <div class="min-h-screen bg-black text-white py-16 px-4">
        <div class="mx-auto max-w-2xl">

            <div class="mb-8">
                <a href="{{ route('projects.index') }}" class="text-sm text-slate-400 hover:text-white transition">
                    ← Back to Projects
                </a>
                <h1 class="mt-4 text-3xl font-bold text-white">Edit Project</h1>
            </div>

            <form action="{{ route('projects.update', $project) }}" method="POST"
                class="space-y-6 rounded-3xl border border-slate-800 bg-slate-900 p-8">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-300">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" placeholder="Enter project name"
                        class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:outline-none" />
                    @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe your project"
                        class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:outline-none">{{ old('description', $project->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none">
                            <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Progress (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="progress" value="{{ old('progress', $project->progress) }}" min="0" max="100"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white focus:border-indigo-500 focus:outline-none" />
                        @error('progress') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    Update Project
                </button>
            </form>

        </div>
    </div>

</x-layout>

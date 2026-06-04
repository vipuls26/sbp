<x-layout title="All Plans">

    <x-header />

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="text-center text-2xl/9 font-bold tracking-tight text-slate-700">Update Plan</h2>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <form action="{{ route('plan.update', $plan) }}" method="POST" class="space-y-6">
            @csrf
            {{-- name --}}
            <div>
                <label for="name" class="block text-sm/6 font-medium text-gray-900">
                    Plan name
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-address-book     absolute p-3 text-gray-400"></i>
                        <input id="name" type="text" name="name" value="{{ old('name', $plan->name) }}"
                            placeholder="Enter Plan Name"
                            class="block w-full rounded-md bg-white
                                        pl-10 pr-3 py-1.5 text-base
                                        text-gray-900 outline-1 -outline-offset-1 outline-slate-200
                                        placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2
                                        focus:outline-slate-700 sm:text-sm/6" />
                    </div>
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- description --}}
            <div>
                <label for="description" class="block text-sm/6 font-medium text-gray-900">
                    Plan description
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-info-circle absolute p-3 text-gray-400"></i>
                        <textarea id="description" name="description" rows="4" placeholder="Enter description about plan"
                            class="block w-full rounded-md bg-white
                                        pl-10 pr-3 py-1.5 text-base
                                        text-gray-900 outline-1 -outline-offset-1 outline-slate-200
                                        placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2
                                        focus:outline-slate-700 sm:text-sm/6">{{ old('description', $plan->description) }}</textarea>
                    </div>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- pricing --}}
            <div>
                <label for="pricing" class="block text-sm/6 font-medium text-gray-900">
                    Plan pricing
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-indian-rupee absolute p-3 text-gray-400"></i>
                        <input id="pricing" type="number" min="0" name="pricing"
                            value="{{ old('pricing', $plan->pricing) }}" placeholder="Enter Plan Price"
                            class="block w-full rounded-md bg-white
                                        pl-10 pr-3 py-1.5 text-base
                                        text-gray-900 outline-1 -outline-offset-1 outline-slate-200
                                        placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2
                                        focus:outline-slate-700 sm:text-sm/6" />
                    </div>
                </div>
                @error('pricing')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- duration --}}
            <div>
                <label for="duration" class="block text-sm/6 font-medium text-gray-900">
                    Duration
                    <span class="text-red-600">*</span>
                </label>

                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-calendar absolute p-3 text-gray-400"></i>

                        <select id="duration" name="duration"
                            class="block w-full rounded-md bg-white
                                        pl-10 pr-3 py-2 text-base
                                        text-gray-900 outline-1 -outline-offset-1 outline-slate-200
                                        focus:outline-2 focus:-outline-offset-2
                                        focus:outline-slate-700 sm:text-sm/6">


                            <option value="monthly"
                                {{ old('duration', $plan->duration) == 'monthly' ? 'selected' : '' }}>
                                Monthly
                            </option>

                            <option value="annual" {{ old('duration', $plan->duration) == 'annual' ? 'selected' : '' }}>
                                Annual
                            </option>

                        </select>
                    </div>
                </div>

                @error('duration')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- button --}}
            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-red-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700">
                    Create Plan </button>
            </div>
        </form>
    </div>

    <x-footer />
</x-layout>

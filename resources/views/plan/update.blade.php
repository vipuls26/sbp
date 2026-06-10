<x-layout title="Update Plan">

    <x-header />

    <div class="mx-auto max-w-2xl px-6 py-12 lg:px-8">

        <form action="{{ route('plans.update', $plan) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')
            {{-- name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">
                    Plan name
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-address-book absolute left-3 top-3 text-slate-400"></i>
                        <input id="name" type="text" name="name" value="{{ old('name', $plan->name) }}"
                            placeholder="Enter Plan Name"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        placeholder:text-slate-400 focus:border-slate-400 focus:outline-none
                                        focus:ring-2 focus:ring-slate-200" />
                    </div>
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">
                    Plan description
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-info-circle absolute left-3 top-3 text-slate-400"></i>
                        <textarea id="description" name="description" rows="4" placeholder="Enter description about plan"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        placeholder:text-slate-400 focus:border-slate-400 focus:outline-none
                                        focus:ring-2 focus:ring-slate-200">{{ old('description', $plan->description) }}</textarea>
                    </div>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- pricing --}}
            <div>
                <label for="pricing" class="block text-sm font-medium text-slate-900">
                    Plan pricing
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-indian-rupee absolute left-3 top-3 text-slate-400"></i>
                        <input id="pricing" type="number" min="0" name="pricing"
                            value="{{ old('pricing', $plan->pricing) }}" placeholder="Enter Plan Price"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        placeholder:text-slate-400 focus:border-slate-400 focus:outline-none
                                        focus:ring-2 focus:ring-slate-200" />
                    </div>
                </div>
                @error('pricing')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- duration --}}
            <div>
                <label for="duration" class="block text-sm font-medium text-slate-900">
                    Duration
                    <span class="text-red-600">*</span>
                </label>

                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-calendar absolute left-3 top-3 text-slate-400"></i>

                        <select id="duration" name="duration"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">


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

            {{-- stripe price id --}}
            <div>
                <label for="stripe_price_id" class="block text-sm font-medium text-slate-900">
                    Stripe price id
                    <span class="text-red-600">*</span>
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-credit-card absolute left-3 top-3 text-slate-400"></i>
                        <input id="stripe_price_id" type="text" name="stripe_price_id"
                            value="{{ old('stripe_price_id', $plan->stripe_price_id) }}"
                            placeholder="price_123456789"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        placeholder:text-slate-400 focus:border-slate-400 focus:outline-none
                                        focus:ring-2 focus:ring-slate-200" />
                    </div>
                </div>
                @error('stripe_price_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- stripe product id --}}
            <div>
                <label for="stripe_product_id" class="block text-sm font-medium text-slate-900">
                    Stripe product id
                </label>
                <div class="mt-2">
                    <div class="relative w-full">
                        <i class="pi pi-box absolute left-3 top-3 text-slate-400"></i>
                        <input id="stripe_product_id" type="text" name="stripe_product_id"
                            value="{{ old('stripe_product_id', $plan->stripe_product_id) }}"
                            placeholder="prod_123456789"
                            class="block w-full rounded-xl border border-slate-200 bg-white
                                        pl-10 pr-3 py-2.5 text-sm text-slate-900
                                        placeholder:text-slate-400 focus:border-slate-400 focus:outline-none
                                        focus:ring-2 focus:ring-slate-200" />
                    </div>
                </div>
                @error('stripe_product_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- features --}}
            <div>
                <label class="block text-sm font-medium text-slate-900">Features</label>
                <div class="mt-2 space-y-2">
                    @foreach (['project' => 'Project', 'team_management' => 'Team Management', 'analytics' => 'Analytics'] as $key => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="features[{{ $key }}]" value="1"
                                {{ old('features.' . $key, $plan->features[$key] ?? false) ? 'checked' : '' }}
                                class="rounded border-slate-300" />
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- button --}}
            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-xl bg-slate-950 px-3 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
</x-layout>

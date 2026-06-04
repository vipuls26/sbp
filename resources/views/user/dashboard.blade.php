<x-layout title="User Dashboard">

    <x-header />

    <div class="relative isolate pt-14 lg:px-8 bg-black text-white min-h-screen py-16 px-4">
        <div class="text-center">
            <p class="mt-8 text-lg font-medium text-pretty text-gray-400 sm:text-xl/8">
                Select any subscription based on your need and enjoy the rest.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('user.plans') }}"
                    class="rounded-md bg-red-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-red-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">Get
                    started</a>
            </div>
        </div>
    </div>

    <x-footer />

</x-layout>

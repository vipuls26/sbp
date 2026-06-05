<header class="bg-black shadow-lg">
    <div class="flex min-h-full flex-col justify-center  lg:px-8">

        <div class="flex items-center justify-between h-16">

            <div class="pl-3 md:pl-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="hover:text-red-500 font-bold {{ Route::is('admin.dashboard') ? 'text-red-500' : 'text-white' }}">
                    Dashboard
                </a>
            </div>

            <nav class="hidden md:flex items-center space-x-6">

                <a href="{{ route('admin.allUsers') }}"
                    class="hover:text-red-500 {{ Route::is('admin.allUsers') ? 'font-bold text-red-500' : 'text-white' }}">
                    Users
                </a>

                <a href="{{ route('plans.index') }}"
                    class="hover:text-red-500 {{ Route::is('plans.index') ? 'font-bold text-red-500' : 'text-white' }}">
                    Plans
                </a>

                <a href="{{ route('admin.subscriber') }}"
                    class="hover:text-red-500 {{ Route::is('admin.subscriber') ? 'font-bold text-red-500' : 'text-white' }}">
                    Subscriber
                </a>

            </nav>

            <div class="pr-3 hidden md:flex">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Logout
                    </button>
                </form>

            </div>

            <button id="menuBtn" class="md:hidden text-white text-2xl pr-3">
                <i class="pi pi-align-justify"></i>
            </button>

        </div>

        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-800 py-4">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('admin.allUsers') }}"
                    class="{{ request()->routeIs('admin.allUsers') ? 'text-red-500 font-bold' : 'text-white' }} hover:text-red-500">
                    Users
                </a>

                <a href="{{ route('plans.index') }}"
                    class="{{ request()->routeIs('plans.index') ? 'text-red-500 font-bold' : 'text-white' }} hover:text-red-500">
                    Plans
                </a>

                <a href="{{ route('admin.subscriber') }}"
                    class="{{ request()->routeIs('admin.subscriber') ? 'text-red-500 font-bold' : 'text-white' }} hover:text-red-500">
                    Subscriber
                </a>

                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Logout
                    </button>
                </form>

            </div>
        </div>

    </div>
</header>

@push('scripts')
    <script>
        document.getElementById('menuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
@endpush

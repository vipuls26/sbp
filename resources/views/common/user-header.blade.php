<header class="bg-black shadow-lg">
    <div class="flex min-h-full flex-col justify-center lg:px-8">

        <div class="flex items-center justify-between h-16">

            <div class="pl-3 md:pl-1">
                <a href="{{ route('user.dashboard') }}"
                    class="hover:text-red-500 font-bold {{ Route::is('user.dashboard') ? 'text-red-500' : 'text-white' }}">
                    Dashboard
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('user.plans') }}"
                    class="hover:text-red-500 {{ Route::is('user.plans') ? 'font-bold text-red-500' : 'text-white' }}">
                    Pricing
                </a>
            </div>

            <div class="hidden md:flex pr-3">
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
                <a class="text-white hover:text-red-500" href="{{ route('user.plans') }}"> Pricing </a>
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

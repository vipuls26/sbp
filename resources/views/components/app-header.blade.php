@props([
    'homeRoute',
    'homeLabel' => 'Dashboard',
    'links' => [],
])

<header class="bg-black shadow-lg">
    <div class="flex min-h-full flex-col justify-center lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="pl-3 md:pl-1">
                <a href="{{ route($homeRoute) }}"
                    class="font-bold hover:text-red-500 {{ request()->routeIs($homeRoute) ? 'text-red-500' : 'text-white' }}">
                    {{ $homeLabel }}
                </a>
            </div>

            <nav class="hidden md:flex items-center space-x-6">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                        class="hover:text-red-500 {{ request()->routeIs($link['route']) ? 'font-bold text-red-500' : 'text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
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
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                        class="{{ request()->routeIs($link['route']) ? 'text-red-500 font-bold' : 'text-white' }} hover:text-red-500">
                        {{ $link['label'] }}
                    </a>
                @endforeach

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

<header class="bg-black shadow-lg">
    <div class="flex min-h-full flex-col justify-center  lg:px-8">

        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <div>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-xl font-bold text-red-500">
                    Admin Dashboard
                </a>
            </div>


            <nav class="hidden md:flex items-center space-x-6">



                <a href="{{ route('plans.index') }}"
                    class="text-white hover:text-red-500">
                    Plans
                </a>

                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Logout
                    </button>
                </form>

            </nav>

         {{-- mobile view --}}
            <button id="menuBtn"
                class="md:hidden text-white text-2xl">
               <i class="pi pi-align-justify"></i>
            </button>

        </div>


        <div id="mobileMenu"
            class="hidden md:hidden border-t border-gray-800 py-4">

            <div class="flex flex-col space-y-3">

                <a href="{{ route('plans.index') }}"
                    class="text-white hover:text-red-500">
                    Plans
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
    document.getElementById('menuBtn').addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>
@endpush

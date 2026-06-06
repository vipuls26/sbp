<x-layout title="Login Page">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">

            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-slate-700">Sign in</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form action="{{ route('auth.authenticate') }}" method="POST" class="space-y-6">
                @csrf
                {{-- email --}}
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">
                        Email address
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2">
                        <div class="relative w-full">
                            <i class="pi pi-envelope absolute p-3 text-gray-400"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Enter Email"
                                class="block w-full rounded-md bg-white
                                        pl-10 pr-3 py-1.5 text-base
                                        text-gray-900 outline-1 -outline-offset-1 outline-slate-200
                                        placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2
                                        focus:outline-slate-700 sm:text-sm/6" />
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- password --}}
                <div>
                    <label for="password" class="block text-sm/6 font-medium text-gray-900">
                        Password <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="pi pi-lock text-gray-400"></i>
                        </div>

                        <input id="password" type="password" name="password" placeholder="Enter Password"
                            class="block w-full rounded-md bg-white pl-10 pr-10 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-slate-200 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-slate-700 sm:text-sm/6" />


                        <button type="button" onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="toggleEyeIcon" class="pi pi-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-red-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700">Login</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-500">
                Don't have account?
                <a href="{{ route('auth.register') }}" class="font-semibold text-red-700 hover:text-red-800">Sign
                    up</a>
            </p>
        </div>
    </div>

</x-layout>


<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('toggleEyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.className = 'pi pi-eye-slash text-gray-400 hover:text-gray-600';
        } else {
            passwordInput.type = 'password';
            eyeIcon.className = 'pi pi-eye text-gray-400 hover:text-gray-600';
        }
    }
</script>

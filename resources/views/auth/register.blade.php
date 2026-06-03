  <x-layout title="Register Page">
      <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
          <div class="sm:mx-auto sm:w-full sm:max-w-sm">

              <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Create your account</h2>
          </div>

          <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
              <form action="{{ route('auth.create') }}" method="POST" class="space-y-6">
                  @csrf

                  {{-- name --}}
                  <div>
                      <label for="name" class="block text-sm/6 font-medium text-gray-900">
                          Name
                          <span class="text-red-600">*</span>
                      </label>
                      <div class="mt-2">
                          <div class="relative w-full">
                              <i class="pi pi-user absolute p-3 text-gray-400"></i>
                              <input id="name" type="text" name="name" value="{{ old('name') }}"
                                  placeholder="Enter Name"
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
                      <label for="password" class="block text-sm/6 font-medium text-gray-900">Password <span
                              class="text-red-600">*</span></label>
                      <div class="mt-2">
                          <div class="relative w-full flex items-center">
                              <i class="pi pi-lock absolute left-3 text-gray-400"></i>
                              <input id="password" type="password" name="password" placeholder="Enter Password"
                                  class="block w-full rounded-md bg-white pl-10 pr-10 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-slate-200 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-slate-700 sm:text-sm/6" />
                              <span class="absolute right-3 cursor-pointer text-gray-400 hover:text-gray-600"
                                  onclick="togglePasswordVisibility('password', 'toggle-eye-password')">
                                  <i id="toggle-eye-password" class="pi pi-eye"></i>
                              </span>
                          </div>
                      </div>
                      @error('password')
                          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  {{-- confirm password --}}
                  <div>
                      <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900"> Confirm
                          Password <span class="text-red-600">*</span> </label>
                      <div class="mt-2">
                          <div class="relative w-full flex items-center">
                              <i class="pi pi-lock absolute left-3 text-gray-400"></i>
                              <input id="password_confirmation" type="password" name="password_confirmation"
                                  placeholder="Enter Confirm Password"
                                  class="block w-full rounded-md bg-white pl-10 pr-10 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-slate-200 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-slate-700 sm:text-sm/6" />
                              <span class="absolute right-3 cursor-pointer text-gray-400 hover:text-gray-600"
                                  onclick="togglePasswordVisibility('password_confirmation', 'toggle-eye-confirm')">
                                  <i id="toggle-eye-confirm" class="pi pi-eye"></i>
                              </span>
                          </div>
                      </div>
                      @error('password_confirmation')
                          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  {{-- Role --}}
                  <div>
                      <label class="block text-sm/6 font-medium text-gray-900">
                          Role <span class="text-red-600">*</span>
                      </label>

                     @if ($roles->isNotEmpty())
                        <fieldset class="mt-2 space-y-4">
                            @foreach ($roles as $role)
                                <div class="flex items-center">
                                    <input id="{{ $role->name }}"
                                        name="role"
                                        type="radio"
                                        value="{{ $role->id }}"
                                        @checked( $role->name  === 'user')
                                        class="role-radio h-4 w-4 border-slate-200 text-slate-700">
                                    <label for="{{ $role->name }}" class="ml-3 text-sm font-medium text-gray-700">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </fieldset>
                        @else
                            <p>Add Role</p>
                        @endif

                      @error('role')
                          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>



                  <div>
                      <button type="submit"
                          class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-red-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700">Register</button>
                  </div>
              </form>


              <p class="mt-10 text-center text-sm/6 text-gray-500">
                  Already have account?
                  <a href="{{ route('auth.login') }}" class="font-semibold text-red-700 hover:text-red-800">Sign
                      in</a>
              </p>
          </div>
      </div>

  </x-layout>


  <script>
      function togglePasswordVisibility(inputId, iconId) {
          const passwordInput = document.getElementById(inputId);
          const eyeIcon = document.getElementById(iconId);

          if (passwordInput.type === "password") {
              passwordInput.type = "text";
              eyeIcon.className = "pi pi-eye-slash";
          } else {
              passwordInput.type = "password";
              eyeIcon.className = "pi pi-eye";
          }
      }
  </script>

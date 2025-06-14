<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6">
                <a href="/" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Home
                </a>
            </div>

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                <p class="text-gray-600 mt-2">Sign in to continue saving lives</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="text-red-600 hover:text-red-500">
                            {{ __('Register now') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Prevent back button from showing cached login page
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.href = '{{ route("dashboard") }}';
            }
        });

        // Clear form data when leaving the page
        window.addEventListener('beforeunload', function() {
            document.getElementById('loginForm').reset();
        });

        // Handle successful login
        document.getElementById('loginForm').addEventListener('submit', function() {
            // Clear any cached data
            sessionStorage.clear();
            localStorage.removeItem('loginForm');
        });
    </script>
</x-app-layout>

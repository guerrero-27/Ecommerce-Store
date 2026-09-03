<x-guest-layout>
    <div class="w-full max-w-sm">
        <h1 class="text-2xl font-semibold text-gray-900 mb-1">Welcome back</h1>
        <p class="text-sm text-gray-500 mb-6">Sign in to your account to continue</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="email" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="password" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-gray-800 shadow-sm focus:ring-gray-400" name="remember">
                    {{ __('Remember me') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                {{ __('Log in') }}
            </button>

            <p class="text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-gray-900 font-medium hover:underline">Sign up</a>
            </p>
        </form>
    </div>
</x-guest-layout>

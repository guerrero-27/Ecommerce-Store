<x-guest-layout>
    <div class="w-full max-w-sm">
        <h1 class="text-2xl font-semibold text-gray-900 mb-1">Create your account</h1>
        <p class="text-sm text-gray-500 mb-6">Let's get started with your free account</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="name" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="email" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="password" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-medium text-gray-700 mb-1" />
                <x-text-input id="password_confirmation" class="block w-full text-sm border-gray-200 rounded-lg focus:ring-0 focus:border-gray-400" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                {{ __('Sign Up') }}
            </button>

            <p class="text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">Log in</a>
            </p>
        </form>
    </div>
</x-guest-layout>

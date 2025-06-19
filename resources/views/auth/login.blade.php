<x-guest-layout>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <x-authentication-card>
        <x-slot name="logo">
            <img src="/assets/images/logo.png" alt="Artemis Logo" width="70" height="70" class="me-2">
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button>
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        {{-- ADDITION: Register Button --}}
        <div class="flex items-center justify-center mt-6">
            <span class="text-sm text-gray-600 me-2">{{ __("Don't have an account?") }}</span>
            <a href="{{ route('register') }}" class="underline text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                {{ __('Register') }}
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>

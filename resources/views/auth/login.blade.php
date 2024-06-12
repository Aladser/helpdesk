<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img class='mx-auto' src="/images/favicon-64.png" alt="Лого">
                <h1 class='text-2xl text-center mt-2'><?=env('APP_NAME')?></h1>
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Логин -->
            <div>
                <p class='text-xl text-center p-2'>Вход в систему</p>
                <x-label for="login" :value="__('Логин')" />

                <x-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('email')" required autofocus />
            </div>

            <!-- Пароль -->
            <div class="mt-4">
                <x-label for="password" :value="__('Пароль')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-button class="ml-3 w-1/2 text-center">{{ __('Войти') }}</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

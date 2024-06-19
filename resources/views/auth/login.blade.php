<x-guest-layout>
    @section('title')
    <title>{{env('APP_NAME')}} - вход в систему</title>
    @endsection

    <x-auth-card>
        <x-slot name="logo">
            <img class='mx-auto' src="/images/favicon-64.png" alt="Лого">
            <h1 class='text-2xl text-center mt-2'>{{env('APP_NAME')}}</h1>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <p class='text-xl text-center p-2'>Вход в систему</p>
            
            <!-- Логин -->
            <div>
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

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 me-3" href="{{ route('register') }}">Зарегистрироваться</a>
                <x-button class="button-theme w-1/3">{{ __('Войти') }}</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

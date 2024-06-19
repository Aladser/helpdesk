<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <img class='mx-auto' src="/images/favicon-64.png" alt="Лого">
            <h1 class='text-2xl text-center mt-2'>{{env('APP_NAME')}}</h1>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <p class='text-xl text-center p-2'>Регистрация пользователя</p>

            <!-- Логин -->
            <div>
                <x-label for="name" :value="__('Логин')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus />
            </div>

            <!-- Пароль -->
            <div class="mt-4">
                <x-label for="password" :value="__('Пароль')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Подтвердите пароль -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Подтвердите пароль')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    Зарегистрированы?
                </a>

                <x-button class="ml-4 button-theme">Зарегистрироваться</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

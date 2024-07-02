<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name='login' content="{{ Auth::user()->login }}">
        <?php // подключение yield из blade?>
        @yield('meta')

        @yield('title')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        @yield('css')
        
        <x-js-script>app.js</x-js-script>
        <script src="https://cdn.tailwindcss.com"></script>
        @yield('js')
        <x-js-script>user_status.js</x-js-script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-theme color-theme">
            @include('layouts.navigation')
            @yield('header')
            <main>{{ $slot }}</main>
        </div>
    </body>
</html>

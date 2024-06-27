<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php // подключение yield из blade?>
        @yield('meta')

        @yield('title')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        @yield('css')
        
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>
        @yield('js')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-theme color-theme">
            @include('layouts.navigation')
            @yield('header')
            <main>{{ $slot }}</main>
        </div>
        <x-js-script>general.js</x-js-script>
    </body>
</html>

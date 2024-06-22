<x-app-layout>
    @section('title')
    <title>{{env('APP_NAME')}} - настройки</title>
    @endsection

    @section('header')
    <x-header>Настройки</x-header>
    @endsection

    @section('css')
    <link rel="stylesheet" href="{{ asset('css/stat.css') }}">
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            Какие-то настройки
       </div>
    </div>
</x-app-layout>

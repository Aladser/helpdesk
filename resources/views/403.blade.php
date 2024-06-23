<x-app-layout>
    @section('title')
    <x-title>ошибка доступа</x-title>
    @endsection

    @section('header')
    <x-header>Ошибка доступа</x-header>
    @endsection

    @section('css')
    <link rel="stylesheet" href="{{ asset('css/stat.css') }}">
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
                <h1 class='font-bold text-2xl'>Доступ запрещен</h1>
       </div>
    </div>
</x-app-layout>

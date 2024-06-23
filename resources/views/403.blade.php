<x-app-layout>
    @section('title')
    <x-title>ошибка доступа</x-title>
    @endsection

    @section('header')
    <x-header>Ошибка доступа</x-header>
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto p-6 bg-white shadow-md text-center">
            <h1 class='font-bold text-2xl'>Доступ запрещен</h1>
            <br>
            <a href="{{route('index')}}" class='button-theme'>На главную</a>
       </div>
    </div>
</x-app-layout>

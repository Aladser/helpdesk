<x-app-layout>
    @section('title')
    <title>{{env('APP_NAME')}} - статистика</title>
    @endsection

    @section('header')
    <x-header>Статистика</x-header>
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            {{$users}}
       </div>
    </div>
</x-app-layout>

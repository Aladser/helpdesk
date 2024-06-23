<x-app-layout>
    @section('title')
    <x-title>создание задачи</x-title>
    @endsection

    @section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection

    @section('header')
    <x-header>Создание задачи</x-header>
    @endsection
    
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    @endsection

    @section('js')
    <script src="/js/pages/create.js" defer></script>
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <div class='w-3/4 mx-auto'>
                <form action="{{route('task.store')}}" method='post' class='w-full'>
                    @csrf
                    <p class='w-full text-center mb-1 text-xl font-semibold'>Тема</p>
                    <input type="text" class='w-full mb-3' name='header' maxlength="100" required>
                    <p class='w-full text-center mb-1 text-xl font-semibold'>Содержание</p>
                    <textarea class='resize-none w-full mb-3' rows=10 name="content" required></textarea>
                    <input type="submit" class='button-theme block w-1/3 mx-auto'>
                </form>
            </div>
       </div>
    </div>
</x-app-layout>

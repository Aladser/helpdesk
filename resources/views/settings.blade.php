<x-app-layout>
    @section('title')
    <x-title>настройки</x-title>
    @endsection

    @section('header')
    <x-header>Настройки</x-header>
    @endsection

    @section('meta')
    <x-meta name='role'>{{ Auth::user()->role->name }}</x-meta>
    <x-meta name='websocket'>{{$websocket_addr}}</x-meta>
    @endsection
    
    @section('js')
    <x-js-script>websockets/ClientWebsocket.js</x-js-script>
    <x-js-script>websockets/websocket_standard.js</x-js-script>
    @endsection
    
    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            Какие-то настройки
       </div>
    </div>
</x-app-layout>

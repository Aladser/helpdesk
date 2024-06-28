<x-app-layout>
    @section('title')
    <x-title>профиль</x-title>
    @endsection

    @section('header')
    <x-header>Профиль</x-header>
    @endsection

    @section('meta')
    <x-meta name='role'>{{ Auth::user()->role->name }}</x-meta>
    <x-meta name='websocket'>{{$websocket_addr}}</x-meta>
    @endsection

    @section('js')
    <x-js-script>websockets/ClientWebsocket.js</x-js-script>
    <x-js-script>websockets/websocket_standart.js</x-js-script>
    @endsection

    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <div class="overflow-hidden sm:rounded-lg">
                <p class='text-2xl pb-2 font-semibold'><span class='inline-block w-32 text-xl'>логин</span> {{$auth_user->login}}</p>
                <p class='text-2xl pb-2'>
                    <span class='inline-block w-32 text-xl'>имя</span> {{$auth_user->full_name}}
                </p>
                <p class='text-2xl pb-2'>
                    <span class='inline-block w-32 text-xl'>роль</span> 
                    {{$auth_user->role->description}}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

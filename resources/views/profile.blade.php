<x-app-layout>
    @section('title')
    <title>{{env('APP_NAME')}} - профиль</title>
    @endsection

    @section('header')
    <x-header>Профиль</x-header>
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
                <p class='text-2xl'>
                    <span class='inline-block w-32 text-xl'>статус</span> 
                    {{$auth_user->status->description}}
                </p>
            </div>
        </div>
    </div>
    
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight color-theme">Профиль</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class='text-2xl pb-2'><span class='inline-block w-32 text-xl'>логин:</span> {{$auth_user->login}}</p>
                    <p class='text-2xl pb-2'>
                        <span class='inline-block w-32 text-xl'>имя:</span> {{$auth_user->full_name()}}
                    </p>
                    <p class='text-2xl'><span class='inline-block w-32 text-xl'>роль:</span> {{$auth_user->role->description}}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name='header'>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Задача № <?=$task->id?></h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 bg-white shadow-md">
            <h3 class="font-semibold text-lg mb-4"><?=$task->header?></h3>
            <p class='mb-2 border-t border-b p-2'><?=$task->content?></p>
            <p>
                <span>Постановщик: </span><?=$task->author->login?>
            </p>

            <p><?=$task->executor->login?></p>
            <p><?=$task->status->name?></p>
            <p><?=$task->created_at?></p>
            <p><?=$task->updated_at?></p>
        </div>
    </div>
</x-app-layout>
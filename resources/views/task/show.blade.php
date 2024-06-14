<x-app-layout>
    <x-slot name='header'>
        <h2 class="font-semibold text-xl leading-tight">Задача № <?=$task->id?></h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <div class='flex justify-between mb-2'>
                @if($task->status->name == 'new')
                <p class='mb-2 font-semibold text-rose-600'><?=$task->status->description?></p>
                @elseif($task->status->name == 'process')
                <p class='mb-2 font-semibold text-amber-500'><?=$task->status->description?></p>
                @elseif($task->status->name == 'completed')
                <p class='mb-2 font-semibold text-green-500'><?=$task->status->description?></p>
                @endif
                
                <p class='font-semibold text-slate-400 italic' title='время создания'><?=$task->created_at->format('d-m-Y h:m')?></p>
            </div>

            <h3 class="font-semibold text-lg mb-4"><?=$task->header?></h3>
            <p class='border-t border-b py-2 mb-2'><?=str_repeat("&nbsp;", 4).$task->content?></p>
            <p class='mb-1'><span>Постановщик: </span><?="{$task->author->full_name()}"?></p>

            @if($task->executor)
            <p class='mb-2'><span>Исполнитель: </span><?="{$task->executor->full_name()}"?></p>
            @endif
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- подключение в layout-->
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    @endsection

    <x-slot name='header'>
        <h2 class="font-semibold text-xl leading-tight"> <?="Задача № {$task->id}"?> </h2>
    </x-slot>

    <div class="py-12 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md mb-4">
            <div class='flex justify-between mb-2 font-semibold'>
                @if($task->status->name == 'new')
                <p class='mb-2 text-rose-600'><?=$task->status->description?></p>
                @elseif($task->status->name == 'process')
                <p class='mb-2 text-amber-500'><?=$task->status->description?></p>
                @elseif($task->status->name == 'completed')
                <p class='mb-2 text-green-500'><?=$task->status->description?></p>
                @endif
                
                <p class='text-slate-400 italic' title='время создания'><?=$task->get_datetime('created_at')?></p>
            </div>

            <h3 class="font-semibold text-lg mb-4"><?=$task->header?></h3>
            <p class='border-t border-b py-2 mb-2'><?=str_repeat("&nbsp;", 4).$task->content?></p>
            <p class='mb-1'><span>Постановщик: </span><?="{$task->author->full_name()}"?></p>

            @if($task->executor)
            <p class='mb-2'><span>Исполнитель: </span><?="{$task->executor->full_name()}"?></p>
            @endif
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <h3 class='font-semibold text-lg mb-2'>Комментарии</h3>
            <div class='relative text-sm'>
                <textarea  rows=3 class='w-full resize-none px-2 py-1 rounded-md' placeholder='Введите сообщение здесь ...'></textarea>
                <button class='btn-submit-comment bg-dark-theme color-light-theme'>Отправить</button>
            </div>

            <div id='cmt-list-block'>
                <div class='cmt-list-block__comment'>
                    <div>
                        <div class='cmt-list-block__author color-lighter-theme'>Коленков К.К.</div>
                        <div class='cmt-list-block__time'>14-06-2024 02:12</div>
                    </div>
                    <div>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации.</div>
                </div>

                <div class='cmt-list-block__comment'>
                    <div>
                        <div class='cmt-list-block__author text-amber-500'>Нестеренко Н.Н.</div>
                        <div class='cmt-list-block__time'>14-06-2024 02:12</div>
                    </div>
                    <div>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации.</div>
                </div>

                <div class='cmt-list-block__comment'>
                    <div>
                        <div class='cmt-list-block__author color-lighter-theme'>Коленков К.К.</div>
                        <div class='cmt-list-block__time'>14-06-2024 02:12</div>
                    </div>
                    <div>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации.</div>
                </div>

                <div class='cmt-list-block__comment'>
                    <div>
                        <div class='cmt-list-block__author text-amber-500'>Нестеренко Н.Н.</div>
                        <div class='cmt-list-block__time'>14-06-2024 02:12</div>
                    </div>
                    <div>Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации.</div>
                </div>             
            </div>
        </div>
    </div>
</x-app-layout>
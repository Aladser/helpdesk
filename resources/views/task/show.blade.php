<x-app-layout>
    <!-- подключение в layout-->
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    @endsection

    <x-slot name='header'><x-header> Задача № {{$task->id}}</x-header></x-slot>

    <div class="py-12 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md mb-4">
            <div class='flex justify-between mb-2 font-semibold'>
                @if($task->status->name == 'new')
                <p class='mb-2 text-rose-600'>{{ $task->status->description }}</p>
                @elseif($task->status->name == 'process')
                <p class='mb-2 text-amber-500'>{{ $task->status->description }}</p>
                @elseif($task->status->name == 'completed')
                <p class='mb-2 text-green-500'>{{ $task->status->description }}</p>
                @endif
                
                <p class='text-slate-400 italic' title='время создания'>{{$task->get_datetime('created_at')}}</p>
            </div>

            <h3 class="font-semibold text-lg mb-4">{{$task->header}}</h3>
            <p class='border-t border-b py-2 mb-3'><?= str_repeat('&nbsp;', 4); ?>{{$task->content}}</p>

            <div class='mb-2'>
                @if($task->status->name == 'new')
                <button id='btn-take-task'class='border px-4 py-2 rounded bg-dark-theme color-light-theme'>Взять в работу</button>
                @elseif($task->status->name == 'process' && $task->executor->id == $auth_user->id)
                <button id='btn-complete-task'class='border px-4 py-2 rounded bg-dark-theme color-light-theme'>Выполнить</button>
                @endif
            </div>
            <p class='mb-1'>Постановщик: {{$task->author->full_name()}}</p>
            
            @if($task->executor)
            <p class='mb-2'>Исполнитель: {{$task->executor->full_name()}}</p>
            @endif
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <h3 class='font-semibold text-lg mb-2'>Комментарии</h3>
            <div class='block-submit relative text-sm'>
                <form methof='POST' action="#">
                    @csrf
                    <input type="hidden" name='author_id' value='{{$auth_user->id}}'>
                    <textarea  rows=3 class='block-submit__textarea' placeholder='Введите сообщение здесь ...' name='message' disabled required></textarea>
                    <input type='submit' class='block-submit__btn bg-dark-theme color-light-theme' disabled>
                </form>
            </div>

            <div id='cmt-list-block'>
                @foreach($comments as $comment)
                <div class='cmt-list-block__comment'>
                    <div>
                        @if($comment->author->role->name == 'executor')
                        <div class='cmt-list-block__author color-lighter-theme'>{{$comment->author->short_full_name()}}</div>
                        @else
                        <div class='cmt-list-block__author text-amber-500'>{{$comment->author->short_full_name()}}</div>
                        @endif

                        <div class='cmt-list-block__time'>{{$comment->created_at}}</div>
                    </div>
                    <div>{{$comment->content}}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
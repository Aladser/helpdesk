<x-app-layout>
    @section('title')
    <x-title>задача № {{$task->id}}</x-title>
    @endsection

    @section('header')
    <x-header>Задача № {{$task->id}}</x-header>
    @endsection
    
    @section('meta')
    <x-meta name='login'>{{ Auth::user()->login }}</x-meta>
    <x-meta name='csrf-token'>{{csrf_token()}}</x-meta>
    @endsection
    
    @section('css')
    <x-css-link>css/show.css</x-css-link>
    @endsection

    @section('js')
    <x-js-script>/js/ServerRequest.js</x-js-script>
    <x-js-script>/js/request_handlers/UpdateTaskStatusHandler.js</x-js-script>
    <x-js-script>/js/request_handlers/StoreCommentHandler.js</x-js-script>
    <x-js-script>/js/pages/show.js</x-js-script>
    @endsection

    <div class="py-8 mx-4">
        <div id='task' class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md mb-4">
            <div class='flex justify-between mb-2 font-semibold'>
                <!--статус-->
                @if($task->status->name == 'new')
                <p id='task__status' class='mb-2 text-rose-600'>{{ $task->status->description }}</p>
                @elseif($task->status->name == 'process')
                <p id='task__status' class='mb-2 text-amber-500'>{{ $task->status->description }}</p>
                @elseif($task->status->name == 'completed')
                <p id='task__status' class='mb-2 text-green-500'>{{ $task->status->description }}</p>
                @endif
                
                <p class='text-slate-400 italic' title='время создания'>{{$task->created_at}}</p>
            </div>
            
            <h3 class="font-semibold text-lg mb-4">{{$task->header}}</h3>
            <p class='border-t border-b py-2 mb-3'><?php echo str_repeat('&nbsp;', 4); ?>{{$task->content}}</p>

            @if($auth_user->role->name !== 'author')
            <!--Кнопки-->
            <div id='task__btn-block' class='mb-2'>
                @if($task->status->name == 'new')
                <button id='btn-complete-task'class='button-theme w-1/6 mb-2 me-1 hidden'>Выполнить</button>
                <button id='btn-take-task'class='button-theme w-1/6 mb-2'>Взять в работу</button>
                <button id='btn-reassign-task'class='button-theme w-1/6 mb-2 me-2 '>Назначить</button>
                @elseif($task->status->name == 'process' && $task->executor->id == $auth_user->id)
                <button id='btn-complete-task'class='button-theme w-1/6 mb-2 me-1 '>Выполнить</button>
                <button id='btn-reassign-task'class='button-theme w-1/6 mb-2'>Переназначить</button>
                @endif

                <!--форма отправки отчета о выполнении задачи-->
                <form id="report-task-form" class="w-1/2 mb-2 hidden">
                    <h3 class="font-semibold">Отчет о работе:</h3>
                    <textarea class="block-submit__textarea" rows="2" name="content"></textarea>
                    <input type="submit" class="button-theme w-1/5 me-1">
                    <button type='button' id='report-form-complete-task__cancel_btn' class="button-theme w-1/5">Отмена</button>
                    @csrf
                </form>
                
                <!--список инженеров для переназначения-->
                <div id='reassign-user-list-block' class='flex hidden'>
                    <form class='w-1/4 me-2'>
                        <select id='reassign-user-form__select' name='reassign_to' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option disabled selected>Кому назначить?</option>
                            @foreach($executors as $executor)
                            <option id="executor-{{$executor->id}}">{{$executor->short_full_name}}</option>
                            @endforeach
                        </select>
                    </form>
                    <button id='reassign-user-list-block__btn-appoint'class='button-theme w-1/6 me-2' disabled>Назначить</button>
                    <button id='reassign-user-list-block__btn-cancel'class='button-theme w-1/6'>Отмена</button>
               </div>
            </div>
            @endif
            
            <p class='mb-1'>Постановщик: {{$task->author->full_name}}</p>
            @if($task->executor)
            <p id='task__executor' class='mb-2'>Исполнитель: {{$task->executor->full_name}}</p>
            @endif
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <h3 class='font-semibold text-lg mb-2'>Комментарии</h3>

            <!-- форма отправки комментария-->
            @if($task->status->name=='new' && $auth_user->role->name != 'author')
            <div id='new-cmt-form-block' class='block-submit relative text-sm hidden'>
            @else
            <div id='new-cmt-form-block' class='block-submit relative text-sm'>
            @endif
                <form id='new-cmt-form' methof='POST' action="{{route('comment.store')}}">
                    @csrf
                    <input id='task__id' name='task_id' type="hidden" value='{{$task->id}}'>
                    <textarea rows=3  id='new-comment-form__textarea' class='block-submit__textarea' placeholder='Введите сообщение здесь ...' name='message' required></textarea>
                    <input type='submit' class='block-submit__btn button-theme'>
                </form>
            </div>

            <!-- комментарии -->
            <div id='cmt-list-block'>
                @foreach($comments as $comment)
                <div class='cmt-list-block__comment'>
                    <div>
                        @if($comment['role'] == 'executor')
                        <div class='cmt-list-block__author color-lighter-theme'>{{$comment['author_name']}}</div>
                        @else
                        <div class='cmt-list-block__author text-amber-500'>{{$comment['author_name']}}</div>
                        @endif

                        <div class='cmt-list-block__time'>
                            {{$comment['created_at']}}
                        </div>
                    </div>
                    <!--для показа тегов в сообщении-->
                    <div>
                        @if($comment['is_report'])
                        <div class='text-green-500 font-semibold'>Задача выполнена</div>
                        @endif
                        <?php echo $comment['content']; ?>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
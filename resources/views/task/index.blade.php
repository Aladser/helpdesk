<x-app-layout>
    <?php // -----подключение секций в blade-шаблон-----?>
    @section('title')
    <x-title></x-title>
    @endsection

    @section('header')
    <x-header>Задачи</x-header>
    @endsection
    
    @section('meta')
    <x-meta name='role'>{{ Auth::user()->role->name }}</x-meta>
    <x-meta name='websocket'>{{$websocket_addr}}</x-meta>
    @endsection
    
    @section('css')
    <x-css-link>css/index.css</x-css-link>
    @endsection

    @section('js')
    <script src="{{asset('/js/websockets/ClientWebsocket.js')}}"></script>
    <script src="{{asset('/js/websockets/IndexClientWebsocket.js')}}"></script>
    <x-js-script>pages/index.js</x-js-script>
    @endsection

    <div class="py-8">
        <div class="mx-auto w-full px-8">
            <div class='flex justify-between items-center'>
                @if($user_role == 'author')
                <a href="{{route('task.create')}}" class='button-theme mb-6 ms-2'>Создать заявку</a>
                @endif
                
                <!--фильтр задач-->
                <form id='task-filter-form' action="{{route('task.index')}}" method='GET'><div class='mb-6 flex'>
                    <div class="flex items-center me-2 ms-2t">
                        <!--все: проверка checked-->
                        @if($task_status=='all')
                        <input checked id="task-filter-form__all" type="radio" value="all" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                        <input id="task-filter-form__all" type="radio" value="all" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Все</label>
                    </div>
                    <div class="flex items-center me-2">
                        <!--новые: проверка checked-->
                        @if($task_status=='new' && !$is_tasks_process)
                        <input checked id="task-filter-form__new" type="radio" value="new" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                        <input id="task-filter-form__new" type="radio" value="new" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Открытые</label>
                    </div>
                    <div class="flex items-center me-2">
                        <!--в работе: проверка checked-->
                        @if($task_status=='process' || $is_tasks_process)
                        <input checked id="task-filter-form__process" type="radio" value="process" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                        <input id="task-filter-form__process" type="radio" value="process" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">В работе</label>
                    </div>
                    <div class="flex items-center">
                        <!--завершены: проверка checked-->
                        @if($task_status=='completed')
                        <input checked id="task-filter-form__completed" type="radio" value="completed" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                        <input id="task-filter-form__completed" type="radio" value="completed" name="filter" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Закрытые</label>
                    </div>
                </div></form>

                <!--фильтр принадлежности задач-->
                @if($user_role != 'author')
                <div class='w-1/5 mb-6'>
                    <form id='belongs-filter-form'>
                        <select id='belongs-filter-form__select' name='belongs' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <!--проверка checked-->    
                            @if($tasks_belongs=='all')
                                <option value="all" selected>Все задачи</option>
                                <option value="my">Мои задачи</option>
                            @else
                                <option value="all">Все задачи</option>
                                <option value="my" selected>Мои задачи</option>
                            @endif
                        </select>
                    </form>
                </div>
                @endif
            </div>


            <table class="task-table shadow-md" id='task-table'>
                <tr class='bg-dark-theme color-light-theme'>
                    <th class='text-center py-2 border-e'>{{$table_headers[0]}}</th>
                    <th class='py-2 border-e'>{{$table_headers[1]}}</th>

                    @if($user_role !== 'author')
                    <th class='text-center py-2 border-e'>{{$table_headers[2]}}</th>
                    @endif
                    
                    @for ($i=3; $i<count($table_headers)-1; $i++)
                    <th class='text-center py-2 border-e'>{{$table_headers[$i]}}</th>
                    
                    @endfor
                    <th class='text-center' title='Последняя активность'>{{$table_headers[6]}}</th>
                </tr>
                @foreach ($tasks as $task)
                <tr class='task-table__row' id="task-{{$task->id}}">
                    <td class='text-center'> {{$task->id}} </td>
                    <td> <a class='task-table__link' href="{{route('task.show', $task->id)}}" class='underline w-1/3 block h-full w-full'>{{$task->header}}</a> </td>
                    
                    @if($user_role !== 'author')
                    <td class='text-center'> {{$task->author->short_full_name}}  </td>
                    @endif

                    <td class='text-center'> {{$task->created_at}} </td>
                    
                    @if ($task->executor)
                    <td class='task-table__executor text-center'> {{$task->executor->short_full_name}}</td>
                    @else
                    <td class='task-table__executor text-center'></td>
                    @endif
                    
                    @if($task->status->name == 'new' & !$is_tasks_process)
                    <td class='task-table__status text-center font-semibold text-rose-600'>{{$task->status->description}}</td>
                    @elseif($task->status->name == 'process' || $is_tasks_process)
                    <td class='task-table__status text-center font-semibold text-amber-500'>{{$task->status->description}}</td>
                    @elseif($task->status->name == 'completed')
                    <td class='task-table__status text-center font-semibold text-green-500'>{{$task->status->description}}</td>
                    @endif

                    <td class='task-table__updated_at text-center'> {{$task->updated_at}} </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>

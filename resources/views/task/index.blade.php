<x-app-layout>
    @section('title')
    <title>{{env('APP_NAME')}} - задачи</title>
    @endsection

    @section('header')
    <x-header>Задачи</x-header>
    @endsection
    
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @endsection

    <div class="py-8">
        <div class="mx-auto w-full px-8">
            @if($user_role == 'author')
            <a href="{{route('task.create')}}" class='button-theme mb-2'>Создать заявку</a>
            @else
            <button id='btn-filter' class='button-theme mb-2'>Фильтры</button>
            @endif

            <table class="task-table shadow-md">
                <tr class='bg-dark-theme color-light-theme'>
                    <td class='text-center'>{{$table_headers[0]}}</td>
                    <td>{{$table_headers[1]}}</td>

                    @if($user_role !== 'author')
                    <td class='text-center'>{{$table_headers[2]}}</td>
                    @endif
                    
                    @for ($i=3; $i<count($table_headers)-1; $i++)
                    <td class='text-center'>{{$table_headers[$i]}}</td>
                    
                    @endfor
                    <td class='text-center' title='Последняя активность'>{{$table_headers[6]}}</td>
                </tr>
                @foreach ($tasks as $task)
                <tr class='task-table__row' id="task-{{$task->id}}">
                    <td class='text-center'> {{$task->id}} </td>
                    <td> <a href="{{route('task.show', $task->id)}}" class='underline w-1/3 block h-full w-full'>{{$task->header}}</a> </td>
                    
                    @if($user_role !== 'author')
                    <td class='text-center'> {{$task->author->short_full_name()}}  </td>
                    @endif

                    <td class='text-center'> {{$task->get_datetime('created_at')}} </td>
                    
                    @if ($task->executor)
                    <td class='text-center'> {{$task->executor->short_full_name()}}</td>
                    @else
                    <td class='text-center'></td>
                    @endif
                    
                    @if($task->status->name == 'new')
                    <td class='text-center font-semibold text-rose-600'>{{$task->status->description}}</td>
                    @elseif($task->status->name == 'process')
                    <td class='text-center font-semibold text-amber-500'>{{$task->status->description}}</td>
                    @elseif($task->status->name == 'completed')
                    <td class='text-center font-semibold text-green-500'>{{$task->status->description}}</td>
                    @endif

                    <td class='text-center'> {{$task->get_datetime('updated_at')}} </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>

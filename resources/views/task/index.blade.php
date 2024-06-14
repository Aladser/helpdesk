<x-app-layout>
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @endsection

    <x-slot name='header'>
        <h2 class="font-semibold text-xl leading-tight">Задачи</h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto w-full px-8">
            <table class="task-table shadow-md">
                <tr class='bg-dark-theme color-light-theme'>
                    <td class='text-center'><?=$table_headers[0]?></td>
                    <td><?=$table_headers[1]?></td>
                    @for ($i=2; $i<count($table_headers); $i++)
                    <td class='text-center'><?=$table_headers[$i]?></td>
                    @endfor
                </tr>
                @foreach ($tasks as $task)
                <tr class='task-table__row' id="task-<?=$task->id?>">
                    <td class='text-center'> <?=$task->id?> </td>
                    <td> <a href="<?=route('task.show', $task->id)?>" class='underline w-1/3'><?=$task->header?></a> </td>
                    <td class='text-center'> <?=$task->author->short_full_name()?>  </td>
                    <td class='text-center'> <?=$task->get_datetime('created_at')?> </td>

                    @if($task->executor)
                    <td class='text-center'> <?=$task->executor->short_full_name()?> </td>
                    @else
                    <td> </td>
                    @endif

                    @if($task->status->name == 'new')
                    <td class='text-center font-semibold text-rose-600'><?=$task->status->description?></td>
                    @elseif($task->status->name == 'process')
                    <td class='text-center font-semibold text-amber-500'><?=$task->status->description?></td>
                    @elseif($task->status->name == 'completed')
                    <td class='text-center font-semibold text-green-500'><?=$task->status->description?></td>
                    @endif

                    <td class='text-center'> <?=$task->get_datetime('updated_at')?> </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>

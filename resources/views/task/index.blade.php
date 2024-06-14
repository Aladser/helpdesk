<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=env('APP_NAME')?> - обращения</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/index.js" defer></script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Профиль</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Войти</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Зарегистрироваться</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="mx-auto w-full px-8">
                <table class="task-table">
                    <tr>
                        <td class='text-center'><?=$table_headers[0]?></td>
                        <td><?=$table_headers[1]?></td>
                        @for ($i=2; $i<count($table_headers); $i++)
                        <td class='text-center'><?=$table_headers[$i]?></td>
                        @endfor
                    </tr>
                    @foreach ($tasks as $task)
                    <tr class='task-table__row' id="task-<?=$task->id?>">
                        <td class='text-center'> <?=$task->id?> </td>
                        <td> <a href="<?=route('task.show', $task->id)?>" class='underline'><?=$task->header?></a> </td>
                        <td class='text-center'> <?=$task->author->short_full_name()?>  </td>
                        <td class='text-center'> <?=$task->created_at->format('d-m-Y h:m')?> </td>

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

                        <td class='text-center'> <?=$task->updated_at->format('d-m-Y h:m')?> </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </body>
</html>

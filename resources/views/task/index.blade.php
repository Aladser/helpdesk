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
                        @foreach ($table_headers as $header)
                            <td><?=$header?></td>
                        @endforeach
                    </tr>
                    @foreach ($tasks as $task)
                    <tr class='task-table__row' id="task-<?=$task->id?>">
                        <td><?=$task->id?></td>
                        <td><?=$task->header?></td>
                        <td><?="{$task->author->surname} {$task->author->name}{$task->author->patronym}"?></td>
                        <td><?=mb_substr($task->created_at, 0, 16)?></td>
                        <td><?="{$task->executor->surname} {$task->executor->name}{$task->executor->patronym}"?></td>
                        <td><?=$task->status->description?></td>
                        <td><?=mb_substr($task->updated_at,0,16)?></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </body>
</html>

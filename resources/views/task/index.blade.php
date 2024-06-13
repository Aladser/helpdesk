<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo env('APP_NAME'); ?></title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
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

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                @foreach ($tasks as $task)
                <div class='border border-current px-24 py-2 mb-2'>
                    <p>ID: <?php echo $task->id; ?></p>
                    <p>Статус: <?php echo $task->status->description; ?></p>
                    <p>Заявитель: <?php echo "{$task->author->surname} {$task->author->name}{$task->author->patronym}"; ?></p>
                    <p>Исполнитель: <?php echo "{$task->executor->surname} {$task->executor->name}{$task->executor->patronym}"; ?></p>
                    <p>Тема: <?php echo $task->header; ?></p>
                    <p>Содержание: <?php echo $task->content; ?></p>
                    <p>Создана: <?php echo $task->created_at; ?></p>
                    <p>Последняя активность: <?php echo $task->updated_at; ?></p>
                </div>
                @endforeach
            </div>
        </div>
    </body>
</html>

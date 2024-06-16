<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
| GET|HEAD  | task                            | task.index          | App\Http\Controllers\TaskController@index   | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| POST      | task                            | task.store          | App\Http\Controllers\TaskController@store   | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/create                     | task.create         | App\Http\Controllers\TaskController@create  | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/{task}                     | task.show           | App\Http\Controllers\TaskController@show    | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| PUT|PATCH | task/{task}                     | task.update         | App\Http\Controllers\TaskController@update  | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| DELETE    | task/{task}                     | task.destroy        | App\Http\Controllers\TaskController@destroy | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/{task}/edit                | task.edit           | App\Http\Controllers\TaskController@edit    | web
*/

class TaskController extends Controller
{
    /**Список заявок*/
    public function index()
    {
        $user_role = Auth::user()->role->name;
        $table_headers = ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'];
        // задачи
        $user = Auth::user();
        if ($user->role->name === 'executor') {
            $tasks = Task::orderBy('updated_at', 'desc')->get();
        } elseif ($user->role->name == 'author') {
            $tasks = Task::where('author_id', $user->id)->orderBy('updated_at', 'desc')->get();
        }

        foreach ($tasks as $task) {
            $task->author->name = mb_substr($task->author->name, 0, 1).'.';
            $task->author->patronym = mb_substr($task->author->patronym, 0, 1).'.';
            if ($task->executor) {
                $task->executor->name = mb_substr($task->executor->name, 0, 1).'.';
                $task->executor->patronym = mb_substr($task->executor->patronym, 0, 1).'.';
            }
        }

        return view('task.index', ['tasks' => $tasks, 'table_headers' => $table_headers, 'user_role' => $user_role]);
    }

    /* Страница задачи */
    public function show($id)
    {
        $comments = Comment::where('task_id', $id)->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment) {
            $comment->created_at = mb_substr($comment->created_at, 0, 16);
        }

        return view(
            'task.show',
            ['auth_user' => Auth::user(), 'task' => Task::find($id), 'comments' => $comments]
        );
    }

    // работает только с CSRF-токеном, PUT-запрос можно отправить только из JS
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $task = Task::find($data['id']);
        $executor = Auth::user();

        if ($data['action'] == 'take-task') {
            $task->status_id = 2;
            $task->executor_id = $executor->id;
            $isUpdated = $task->save();
        } elseif ($data['action'] == 'complete-task') {
            $task->status_id = 3;
            $isUpdated = $task->save();
        } else {
            return ['is_updated' => -1];
        }

        return json_encode([
            'is_updated' => (int) $isUpdated,
            'action' => $data['action'],
            'executor' => $executor->full_name(),
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function destroy($id)
    {
    }
}

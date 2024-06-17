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
*/

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $task_filter = isset($data['filter']) ? $data['filter'] : 'new';
        $user_role = Auth::user()->role->name;
        $table_headers = ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'];
        $user = Auth::user();

        // задачи
        if ($user->role->name === 'executor') {
            switch ($task_filter) {
                case 'all':
                    $tasks = Task::orderBy('updated_at', 'desc')->get();
                    break;
                case 'new':
                    $tasks = Task::where('status_id', 1)->orderBy('updated_at', 'desc')->get();
                    break;
                case 'process':
                    $tasks = Task::where('status_id', 2)->orderBy('updated_at', 'desc')->get();
                    break;
                case 'completed':
                    $tasks = Task::where('status_id', 3)->orderBy('updated_at', 'desc')->get();
                    break;
                default:
                    return;
            }
        } elseif ($user->role->name == 'author') {
            switch ($task_filter) {
                case 'all':
                    $tasks = Task::where('author_id', $user->id)->orderBy('updated_at', 'desc')->get();
                    break;
                case 'new':
                    $tasks = Task::where('author_id', $user->id)->where('status_id', 1)->orderBy('updated_at', 'desc')->get();
                    break;
                case 'process':
                    $tasks = Task::where('author_id', $user->id)->where('status_id', 2)->orderBy('updated_at', 'desc')->get();
                    break;
                case 'completed':
                    $tasks = Task::where('author_id', $user->id)->where('status_id', 3)->orderBy('updated_at', 'desc')->get();
                    break;
                default:
                    return;
            }
        }

        foreach ($tasks as $task) {
            $task->author->name = mb_substr($task->author->name, 0, 1).'.';
            $task->author->patronym = mb_substr($task->author->patronym, 0, 1).'.';
            if ($task->executor) {
                $task->executor->name = mb_substr($task->executor->name, 0, 1).'.';
                $task->executor->patronym = mb_substr($task->executor->patronym, 0, 1).'.';
            }
        }

        return view(
            'task.index',
            [
                'tasks' => $tasks,
                'table_headers' => $table_headers,
                'user_role' => $user_role,
                'task_filter' => $task_filter,
            ]
        );
    }

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

    public function update(Request $request, $id)
    {
        // работает только с CSRF-токеном, PUT-запрос можно отправить только из JS
        $data = $request->all();
        $task = Task::find($id);
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
        return view('task.create', ['auth_user' => Auth::user()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $task = new Task();
        $task->status_id = 1;
        $task->author_id = Auth::user()->id;
        $task->header = $data['header'];
        $task->content = $data['content'];
        $task->save();

        return redirect()->route('task.show', $task->id);
    }
}

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
    private $task_filters = ['new' => 1, 'process' => 2, 'completed' => 3];

    public function index(Request $request)
    {
        $data = $request->all();
        $task_status = isset($data['type']) ? $data['type'] : 'new';
        $task_belongs = isset($data['belongs']) ? $data['belongs'] : 'new';

        $user = Auth::user();
        $table_headers = ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'];

        // задачи
        if ($task_status == 'all') {
            if ($task_belongs == 'my' && $user->role->name == 'executor' && $task_status == 'all') {
                // исполнитель: мои, все
                $new_tasks = Task::where('status_id', 1)->orderBy('updated_at', 'desc');
                $tasks = Task::where('executor_id', $user->id)->orderBy('updated_at', 'desc')->union($new_tasks)->get();
            } elseif ($task_belongs == 'my' && $user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: мои, неновые
                $tasks = Task::where('executor_id', $user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($user->role->name == 'author') {
                // автор
                $tasks = Task::where('author_id', $user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                $tasks = Task::orderBy('updated_at', 'desc')->get();
            }
        } else {
            if ($task_belongs == 'my' && $user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: мои, неновые
                $tasks = Task::where('status_id', $this->task_filters[$task_status])->where('executor_id', $user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($user->role->name == 'author') {
                // автор
                $tasks = Task::where('status_id', $this->task_filters[$task_status])->where('author_id', $user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                $tasks = Task::where('status_id', $this->task_filters[$task_status])->orderBy('updated_at', 'desc')->get();
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
                'user_role' => $user->role->name,
                'task_status' => $task_status,
                'task_belongs' => $task_belongs,
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

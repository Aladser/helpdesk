<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
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
        $auth_user = Auth::user();
        $data = $request->all();
        $task_status = isset($data['type']) ? $data['type'] : 'new';
        $task_belongs = isset($data['belongs']) ? $data['belongs'] : 'new';
        $table_headers = ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'];

        // задачи
        if ($task_status == 'all') {
            if ($task_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status == 'all') {
                // исполнитель: мои, все
                $new_tasks = Task::where('status_id', 1);
                $tasks = Task::where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->union($new_tasks)->orderBy('updated_at', 'desc')->get();
            } elseif ($task_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: мои, неновые
                $tasks = Task::where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($auth_user->role->name == 'author') {
                // автор
                $tasks = Task::where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                $tasks = Task::orderBy('updated_at', 'desc')->get();
            }
        } else {
            if ($task_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: мои, неновые
                $tasks = Task::where('status_id', $this->task_filters[$task_status])->where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($auth_user->role->name == 'author') {
                // автор
                $tasks = Task::where('status_id', $this->task_filters[$task_status])->where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
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

        $request_data = [
            'tasks' => $tasks,
            'table_headers' => $table_headers,
            'user_role' => $auth_user->role->name,
            'task_status' => $task_status,
            'task_belongs' => $task_belongs,
        ];

        return view('task.index', $request_data);
    }

    public function show($id)
    {
        $auth_user = Auth::auth_user();
        $comments = Comment::where('task_id', $id)->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment) {
            $comment->created_at = mb_substr($comment->created_at, 0, 16);
        }

        $request_data = ['auth_user' => Auth::auth_user(), 'task' => Task::find($id), 'comments' => $comments];
        // исполнители
        if ($auth_user->role->name !== 'author') {
            $executor_arr = [];
            $executor_list = User::where('role_id', 2)->get();
            foreach ($executor_list as $executor) {
                if ($executor->id != $auth_user->id) {
                    $executor_arr[] = ['id' => $executor->id, 'name' => $executor->short_full_name()];
                }
            }
            $request_data['executors'] = $executor_arr;
        }

        return view('task.show', $request_data);
    }

    // работает только с CSRF-токеном, PUT-запрос можно отправить только из JS
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $task = Task::find($id);
        $executor = Auth::auth_user();

        if ($data['action'] == 'take-task') {
            $task->status_id = 2;
            $task->executor_id = $executor->id;
            $isUpdated = $task->save();
        } elseif ($data['action'] == 'complete-task') {
            $task->status_id = 3;
            $isUpdated = $task->save();

            if ($isUpdated) {
                // сохранение отчета в комментариях
                $comment = new Comment();
                $comment->task_id = $id;
                $comment->author_id = $executor->id;
                $comment->content = "
                    <p class='text-gray-400'>Задача выполнена</p>
                    <p class='ps-1'>{$data['content']}</p>
                ";
                $comment->save();
            }
        } else {
            return ['is_updated' => -1];
        }

        // ответ сервера
        $response_data = [
            'is_updated' => (int) $isUpdated,
            'action' => $data['action'],
            'executor' => $executor->full_name(),
        ];
        if ($data['action'] == 'complete-task' && $isUpdated) {
            $response_data['task_completed_report'] = $comment->content;
            $response_data['task_completed_date'] = Carbon::now()->format('Y:m:d H:i');
            $response_data['executor_short_full_name'] = $executor->short_full_name();
        }

        return json_encode($response_data);
    }

    public function create()
    {
        return view('task.create', ['auth_user' => Auth::auth_user()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $task = new Task();
        $task->status_id = 1;
        $task->author_id = Auth::auth_user()->id;
        $task->header = $data['header'];
        $task->content = $data['content'];
        $task->save();

        return redirect()->route('task.show', $task->id);
    }
}

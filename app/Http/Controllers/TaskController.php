<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    private array $task_filters = ['new' => 1, 'process' => 2, 'completed' => 3];

    public function index(Request $request)
    {
        $auth_user = Auth::user();
        $data = $request->all();

        // проверка наличия рабочих задач исполнителя, если открывается страница без фильтров
        $is_tasks_process = false;
        if (!isset($data['type']) && !isset($data['belongs'])) {
            $task_arr = Task::where('status_id', $this->task_filters['process']);
            if ($auth_user->role->name != 'author') {
                $is_tasks_process = $task_arr->where('executor_id', $auth_user->id)->count() > 0;
            } else {
                $is_tasks_process = $task_arr->where('author_id', $auth_user->id)->count() > 0;
            }
        }

        $task_status = $data['type'] ?? 'new';
        $tasks_belongs = $data['belongs'] ?? 'my';

        // задачи
        if ($task_status == 'all') {
            if ($tasks_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status == 'all') {
                // исполнитель: все+новые + мои
                $new_tasks = Task::where('status_id', 1);
                $task_arr = Task::where('executor_id', $auth_user->id)->union($new_tasks)->orderBy('updated_at', 'desc')->get();
            } elseif ($tasks_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: все+мои
                $task_arr = Task::where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($auth_user->role->name == 'author') {
                // автор: все
                $task_arr = Task::where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                // все
                $task_arr = Task::orderBy('updated_at', 'desc')->get();
            }
        } else {
            if ($is_tasks_process) {
                $task_arr = Task::where('status_id', $this->task_filters['process']);
                if ($auth_user->role->name != 'author') {
                    $task_arr = $task_arr->where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
                } else {
                    $task_arr = $task_arr->where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
                }
            } elseif ($tasks_belongs == 'my' && $auth_user->role->name == 'executor' && $task_status != 'new') {
                // исполнитель: !все+мои
                $task_arr = Task::where('status_id', $this->task_filters[$task_status])->where('executor_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } elseif ($auth_user->role->name == 'author') {
                // автор: !все+
                $task_arr = Task::where('status_id', $this->task_filters[$task_status])->where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                // !все
                $task_arr = Task::where('status_id', $this->task_filters[$task_status])->orderBy('updated_at', 'desc')->get();
            }
        }

        $request_data = [
            'tasks' => $task_arr,
            'table_headers' => ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'],
            'user_role' => $auth_user->role->name,
            'task_status' => $task_status,
            'tasks_belongs' => $tasks_belongs,
            'is_tasks_process' => $is_tasks_process,
        ];

        return view('task.index', $request_data);
    }

    public function show($id)
    {
        $auth_user = Auth::user();

        $comments = Comment::where('task_id', $id)->orderBy('created_at', 'desc')->get();
        $comments_arr = [];
        foreach ($comments as $comment) {
            $comments_arr[] = [
                'role' => $comment->author->role->name,
                'author_name' => $comment->author->short_full_name,
                'created_at' => $comment->created_at,
                'is_report' => $comment->is_report,
                'content' => str_replace(PHP_EOL, '<br>', $comment->content),
            ];
        }
        $request_data = ['auth_user' => $auth_user, 'task' => Task::find($id), 'comments' => $comments_arr];

        // список исполнителей переадресации для исполнителя
        if ($auth_user->role->name !== 'author') {
            $request_data['executors'] = User::where('role_id', 2)->where('id', '<>', $auth_user->id)->select('id', 'name', 'surname', 'patronym')->get();
        }

        return view('task.show', $request_data);
    }

    // работает только с CSRF-токеном, PUT-запрос можно отправить только из JS
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        $data = $request->all();

        // проверка на наличие назначения заявки другим пользователем
        if (is_null($data['assigned_person'])) {
            $executor = Auth::user();
            $is_assigned = false;
        } else {
            $executor = User::find($data['assigned_person']);
            $is_assigned = true;
        }

        if ($data['action'] == 'take-task') {
            // взять в работу

            $task->status_id = 2;
            $task->executor_id = $executor->id;
            $isUpdated = $task->save();
        } elseif ($data['action'] == 'complete-task') {
            // выполнить задачу

            $is_report = false;
            $task->status_id = 3;
            $isUpdated = $task->save();

            if ($isUpdated) {
                // сохранение отчета в комментариях
                $is_report = true;
                $comment = new Comment();
                $comment->task_id = $id;
                $comment->author_id = $executor->id;
                $comment->is_report = true;
                $comment->content = $data['content'] ?? '';
                $comment->save();
            }
        } else {
            return ['is_updated' => false];
        }

        // ответ сервера
        $response_data = [
            'is_updated' => $isUpdated,
            'is_assigned' => $is_assigned,
            'action' => $data['action'],
            'executor' => $executor->full_name,
        ];
        if ($data['action'] == 'complete-task' && $isUpdated) {
            $response_data['task_completed_report'] = $comment->content;
            $response_data['task_completed_date'] = Carbon::now()->format('d-m-Y H:i');
            $response_data['task_completed_is_report'] = $is_report;
            $response_data['executor_short_full_name'] = $executor->short_full_name;
        }

        return json_encode($response_data);
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

    public function stat(Request $request)
    {
        $tasks_arr = Task::all();
        $executors_arr = DB::table('users')->join('user_roles', 'users.role_id', '=', 'user_roles.id')->where('user_roles.name', 'executor')->get();
        dd($executors_arr);
    }
}

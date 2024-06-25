<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\UserRole;
use App\Services\WebsocketService;
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
    private array $task_filters = ['new' => 1, 'process' => 2, 'completed' => 3];
    private string $websocket_addr;

    public function __construct()
    {
        $this->websocket_addr = WebsocketService::getWebsockerAddr();
    }

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
                // автор: все авторские
                $task_arr = Task::where('status_id', $this->task_filters[$task_status])->where('author_id', $auth_user->id)->orderBy('updated_at', 'desc')->get();
            } else {
                // !все
                $task_arr = Task::where('status_id', $this->task_filters[$task_status])->orderBy('updated_at', 'desc')->get();
            }
        }

        $request_data = [
            'type' => 'new-task',
            'tasks' => $task_arr,
            'table_headers' => ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'],
            'user_role' => $auth_user->role->name,
            'task_status' => $task_status,
            'tasks_belongs' => $tasks_belongs,
            'is_tasks_process' => $is_tasks_process,
            'websocket_addr' => $this->websocket_addr,
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
        $request_data = [
            'auth_user' => $auth_user,
            'task' => Task::find($id),
            'comments' => $comments_arr,
            'websocket_addr' => $this->websocket_addr,
        ];

        // список исполнителей переадресации для исполнителя
        if ($auth_user->role->name !== 'author') {
            $request_data['executors'] = User::where('role_id', 2)->where('id', '<>', $auth_user->id)->select('id', 'name', 'surname', 'patronym')->get();
        }

        return view('task.show', $request_data);
    }

    public function update(Request $request, $id)
    {
        // работает только с CSRF-токеном, PUT-запрос можно отправить только из JS
        $task = Task::find($id);
        $data = $request->all();
        $author = User::find($task->author_id);

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
            $is_updated = $task->save();
        } elseif ($data['action'] == 'complete-task') {
            // выполнить задачу

            $is_report = false;
            $task->status_id = 3;
            $is_updated = $task->save();

            if ($is_updated) {
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

        // отправка информации в вебсокет
        if ($is_updated) {
            WebsocketService::send([
                'type' => $data['action'],
                'id' => $task->id,
                'header' => $task->header,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
                'author_name' => $author->short_full_name,
                'author_login' => $author->login,
                'executor_name' => $executor->short_full_name,
                'executor_login' => $executor->login,
            ]);
        }

        // ответ сервера
        $response_data = [
            'is_updated' => $is_updated,
            'is_assigned' => $is_assigned,
            'action' => $data['action'],
            'executor' => $executor->full_name,
        ];
        if ($data['action'] == 'complete-task' && $is_updated) {
            $response_data['task_completed_report'] = $comment->content;
            $response_data['task_completed_date'] = $task->updated_at;
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
        $is_updated = $task->save();

        // отправка информации в вебсокет
        if ($is_updated) {
            WebsocketService::send([
                'type' => 'task-new',
                'id' => $task->id,
                'header' => $data['header'],
                'author_name' => Auth::user()->short_full_name,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ]);
        }

        return redirect()->route('task.show', $task->id);
    }

    public function stat(Request $request)
    {
        $tasks_arr = Task::all();

        $executor_role_id = UserRole::where('name', 'executor')->select('id')->first()['id'];
        $executors_arr = User::where('role_id', $executor_role_id)->get();

        // список id статусов задач
        $status_id_arr = [
            'new' => TaskStatus::where('name', 'new')->select('id')->first()['id'],
            'process' => TaskStatus::where('name', 'process')->select('id')->first()['id'],
            'completed' => TaskStatus::where('name', 'completed')->select('id')->first()['id'],
        ];
        $new_tasks_count = Task::where('status_id', $status_id_arr['new'])->count();
        $total_process_tasks_count = Task::where('status_id', $status_id_arr['process'])->count();
        $total_completed_tasks_count = Task::where('status_id', $status_id_arr['completed'])->count();

        // статистика исполнителей
        $executors_stat_arr = [];
        foreach ($executors_arr as $executor) {
            // число задач в процессе
            $process_tasks_count = Task::where('executor_id', $executor->id)->where('status_id', $status_id_arr['process'])->count();
            if ($total_process_tasks_count != 0) {
                $process_tasks_count_percent = round(($process_tasks_count / $total_process_tasks_count) * 100);
            } else {
                $process_tasks_count_percent = 0;
            }
            // число выполненных задач
            $completed_tasks_count = Task::where('executor_id', $executor->id)->where('status_id', $status_id_arr['completed'])->count();
            if ($total_completed_tasks_count != 0) {
                $completed_tasks_count_percent = round(($completed_tasks_count / $total_completed_tasks_count) * 100);
            } else {
                $completed_tasks_count_percent = 0;
            }

            $executors_stat_arr[$executor->id] = [
                'name' => $executor->short_full_name,
                'process_count' => $process_tasks_count,
                'process_count_percent' => $process_tasks_count_percent,
                'completed_count' => $completed_tasks_count,
                'completed_count_percent' => $completed_tasks_count_percent,
            ];
        }

        return view(
            'stat',
            [
                'table_headers' => ['Пользователь', 'Число заявок в работе', 'Число завершенных заявок'],
                'new_tasks_count' => $new_tasks_count,
                'process_tasks_count' => $total_process_tasks_count,
                'completed_tasks_count' => $total_completed_tasks_count,
                'executors_stat_arr' => $executors_stat_arr,
            ]
        );
    }
}

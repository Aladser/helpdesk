<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Services\WebsocketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $auth_user = Auth::user();
        $data = $request->all();
        $data['author_name'] = $auth_user->short_full_name;
        $data['author_role'] = $auth_user->role->name;
        $data['created_at'] = Carbon::now();

        // обновление активности задачи
        $task = Task::find($data['task_id']);
        $task->updated_at = $data['created_at'];
        $task->save();

        // добавление комментария
        $comment = new Comment();
        $comment->task_id = $data['task_id'];
        $comment->author_id = $auth_user->id;
        $comment->content = $data['message'];
        $comment->created_at = $data['created_at'];
        $data['is_stored'] = $comment->save();

        $data['message'] = str_replace(PHP_EOL, '<br>', $data['message']);
        $data['created_at'] = $data['created_at']->format('d-m-Y H:i');
        unset($data['_token']);
        unset($data['task_id']);

        // отправка информации в вебсокет
        if ($data['is_stored']) {
            WebsocketService::send([
                'type' => 'comment-new',
                'time' => $data['created_at'],
                'author_name' => $data['author_name'],
                'author_role' => $data['author_role'],
                'author_login' => User::find($task->author_id)->login,
                'executor_login' => User::find($task->executor_id)->login,
                'content' => $comment->content,
                'task_id' => $comment->task_id,
            ]);
        }

        return $data;
    }
}

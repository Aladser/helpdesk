<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentImage;
use App\Models\Task;
use App\Models\User;
use App\Services\WebsocketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /** папка для хранения изображений */
    private string $mediaFolder;

    public function __construct()
    {
        $this->mediaFolder = dirname(__FILE__, 4).'/'.env('MEDIA_ROOT').'/';
    }

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

        // добавление комментария в БД
        $comment = new Comment();
        $comment->task_id = $data['task_id'];
        $comment->author_id = $auth_user->id;
        $comment->content = $data['message'] ? $data['message'] : '';
        $comment->created_at = $data['created_at'];
        $data['is_stored'] = $comment->save();

        // загрузка изображения в папку media
        if (gettype($_FILES['images']['tmp_name']) == 'array') {
            try {
                for ($i = 0; $i < count($_FILES['images']['tmp_name']); ++$i) {
                    // формирование имени файла
                    $image_name = $comment->id.'_'.$_FILES['images']['name'][$i];

                    // сохранение изображения из кэша браузера
                    $is_uploaded = move_uploaded_file($_FILES['images']['tmp_name'][$i], $this->mediaFolder.$image_name);

                    // добавление изображения в БД
                    $comment_image = new CommentImage();
                    $comment_image->name = $image_name;
                    $comment_image->comment_id = $comment->id;
                    $comment_image->save();
                }
            } catch (\Exception $e) {
                var_dump($e);
            }
        }

        // отправка комментария в вебсокет
        if ($data['is_stored']) {
            WebsocketService::send([
                'type' => 'comment-new',
                'created_at' => $data['created_at'],
                'author_name' => $data['author_name'],
                'author_role' => $data['author_role'],
                'author_login' => User::find($task->author_id)->login,
                'executor_login' => User::find($task->executor_id)->login,
                'content' => $data['message'],
                'task_id' => $data['task_id'],
                'is_report' => $data['is_report'] ?? false,
            ]);
        }

        unset($data['_token']);
        unset($data['task_id']);
        $data['message'] = str_replace(PHP_EOL, '<br>', $data['message']);
        $data['created_at'] = $data['created_at']->format('d-m-Y H:i');

        return $data;
    }
}

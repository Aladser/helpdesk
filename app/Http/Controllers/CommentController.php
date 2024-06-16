<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Status;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $auth_user = Auth::user();
        $data = $request->all();
        $data['executor_name'] = $auth_user->short_full_name();
        $data['created_at'] = Carbon::now();

        $comment = new Comment();
        $comment->task_id = $data['task_id'];
        $comment->author_id = $auth_user->id;
        $comment->content = $data['message'];
        $comment->created_at = $data['created_at'];
        $data['isStored'] = $comment->save();
        $data['created_at'] = $data['created_at']->format('Y-m-d H:i');
        unset($data['_token']);
        unset($data['task_id']);

        return $data;

    }
}

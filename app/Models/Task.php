<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $timestamps = true;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id', 'id');
    }

    public function get_datetime(string $type): string
    {
        if ($type == 'created_at') {
            return $this->created_at->format('d-m-Y H:i');
        } elseif ($type == 'updated_at') {
            return $this->updated_at->format('d-m-Y H:i');
        } else {
            return '';
        }
    }
}

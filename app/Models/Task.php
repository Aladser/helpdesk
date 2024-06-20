<?php

namespace App\Models;

use Carbon\Carbon;
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

    // форматированная дата создания
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:m');
    }

    // форматированная дата обновления
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:m');
    }
}

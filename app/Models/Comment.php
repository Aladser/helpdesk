<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    private static $publicDateFormat = 'd-m-Y H:m';

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    // форматированная дата создания
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(self::$publicDateFormat);
    }
}

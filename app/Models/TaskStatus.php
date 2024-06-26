<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}

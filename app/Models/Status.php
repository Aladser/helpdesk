<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    // без времени создания
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    // пользователи
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}

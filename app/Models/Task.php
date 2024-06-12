<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'status',
    ];

    // автор задачи
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    // исполнитель задачи
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }
}

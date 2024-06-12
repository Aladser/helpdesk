<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // без времени создания
    public $timestamps = false;

    protected $fillable = [
        'name',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // роль
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }

    // авторы задач
    public function authors()
    {
        return $this->hasMany(Task::class, 'author_id', 'id');
    }

    // исполнители задач
    public function executors()
    {
        return $this->hasMany(Task::class, 'executor_id', 'id');
    }
}

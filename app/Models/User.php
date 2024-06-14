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
        'password',
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

    public function full_name() {
        return "{$this->surname} {$this->name} {$this->patronym}";
    }

    public function short_full_name() {
        $name = mb_substr($this->name, 0, 1).'.';
        $patronym = mb_substr($this->patronym, 0, 1).'.';
        return "{$this->surname} $name $patronym";
    }
}

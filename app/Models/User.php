<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'status_id', 'id');
    }

    public function authors()
    {
        return $this->hasMany(Task::class, 'author_id', 'id');
    }

    public function executors()
    {
        return $this->hasMany(Task::class, 'executor_id', 'id');
    }

    public function commentators()
    {
        return $this->hasMany(Comment::class, 'author_id', 'id');
    }

    // полное имя
    public function GetFullNameAttribute()
    {
        return "{$this->surname} {$this->name} {$this->patronym}";
    }

    // сокращенное полное имя
    public function GetShortFullNameAttribute()
    {
        $name = mb_substr($this->name, 0, 1).'.';
        $patronym = mb_substr($this->patronym, 0, 1).'.';

        return "{$this->surname} $name $patronym";
    }
}

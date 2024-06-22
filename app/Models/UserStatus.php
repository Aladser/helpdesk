<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    public $timestamps = false;

    // пользователи
    public function users()
    {
        return $this->hasMany(User::class, 'status_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;

    // пользователи
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}

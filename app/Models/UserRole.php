<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    // без времени создания
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    // пользователи
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}

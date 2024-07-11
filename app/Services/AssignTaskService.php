<?php

namespace App\Services;

use App\Models\Connection;

// Сервис распределения заявок
class AssignTaskService
{
    public static function getFreeOnlineWorkers()
    {
        return Connection::where('is_active', true)->select('user_id')->get();
    }
}

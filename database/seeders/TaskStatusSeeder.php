<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        TaskStatus::create(['name' => 'new', 'description' => 'Новая']);
        TaskStatus::create(['name' => 'process', 'description' => 'В работе']);
        TaskStatus::create(['name' => 'completed', 'description' => 'Выполнена']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        for ($i=0;$i<3; $i++) {
            Task::create([
                'status' => "new",
                'executor_id' => $i+2,
                'author_id' => $i+5,
            ]);
        }
    }
}
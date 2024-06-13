<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        Status::create(['name' => "new",'description' => "Новая",]);
        Status::create(['name' => "process",'description' => "В работе",]);
        Status::create(['name' => "completed",'description' => "Выполнена",]);
    }
}

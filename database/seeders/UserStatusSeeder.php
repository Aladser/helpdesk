<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    public function run()
    {
        UserStatus::create(['name' => 'active', 'description' => 'Активен']);
        UserStatus::create(['name' => 'non_active', 'description' => 'Неактивен']);
        UserStatus::create(['name' => 'disabled', 'description' => 'Отключен']);
    }
}

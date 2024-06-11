<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        UserRole::create(['name' => "admin",'description' => "Администратор",]);
        UserRole::create(['name' => "executor",'description' => "Исполнитель",]);
        UserRole::create(['name' => "client",'description' => "Заявитель",]);
    }
}

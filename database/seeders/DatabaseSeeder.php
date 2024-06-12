<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // запуск миграции с сидами: php artisan migrate:refresh --seed
    public function run()
    {
        $userRoleSeeder = new UserRoleSeeder();
        $userSeeder = new UserSeeder();
        $taskSeeder = new TaskSeeder();

        $userRoleSeeder->run();
        $userSeeder->run();
        $taskSeeder->run();
    }
}

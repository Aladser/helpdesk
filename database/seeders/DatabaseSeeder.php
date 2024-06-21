<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // запуск миграции с сидами: php artisan migrate:refresh --seed
    public function run()
    {
        $userRoleSeeder = new UserRoleSeeder();
        $userStatusSeeder = new UserStatusSeeder();
        $userSeeder = new UserSeeder();
        $statusSeeder = new StatusSeeder();
        $taskSeeder = new TaskSeeder();
        $commentSeeder = new CommentSeeder();

        $userRoleSeeder->run();
        $userStatusSeeder->run();
        $userSeeder->run();

        $statusSeeder->run();
        $taskSeeder->run();
        $commentSeeder->run();
    }
}

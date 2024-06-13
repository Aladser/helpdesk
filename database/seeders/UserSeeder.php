<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Пароль: 12345678
        User::create([
            'login' => "admin",
            'role_id' => 1,
            'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',
            'name' => 'Админ',
            'surname' => 'Админов',
            'patronym' => 'Админыч'
        ]);
        for ($i = 1; $i < 4; ++$i) {
            User::create([
                'login' => "executor_$i",
                'role_id' => 2,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
                'name' => "Имя-И$i",
                'surname' => "Фамилия-И$i",
                'patronym' => "Отчество-И$i"
            ]);
        }
        for ($i = 1; $i < 4; ++$i) {
            User::create([
                'login' => "client_$i",
                'role_id' => 3,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
                'name' => "Имя-П$i",
                'surname' => "Фамилия-П$i",
                'patronym' => "Отчество-П$i"
            ]);
        }
    }
}

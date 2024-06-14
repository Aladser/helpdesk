<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $surnames = ['Казаков','Харитонов','Дмитриев', 'Аксёнова','Хохлова','Селезнёва'];
    private $names = ['Нисон','Мечеслав','Анатолий','Юнона','Ася','Нева'];
    private $patronyms = ['Борисович','Антонович','Макарович','Пётровна','Витальевна','Даниловна'];

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
        for ($i = 0; $i < 3; ++$i) {
            User::create([
                'login' => "executor_".($i+1),
                'role_id' => 2,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
                'name' => $this->names[$i],
                'surname' => $this->surnames[$i],
                'patronym' => $this->patronyms[$i]
            ]);
        }
        for ($i = 3; $i < 6; ++$i) {
            User::create([
                'login' => "client_".($i+1),
                'role_id' => 3,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
                'name' => $this->names[$i],
                'surname' => $this->surnames[$i],
                'patronym' => $this->patronyms[$i]
            ]);
        }
    }
}

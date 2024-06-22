<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private $surnames = ['Помидоркин', 'Птичкин', 'Змеев', 'Аксёнова', 'Хохлова', 'Селезнёва'];
    private $names = ['Николай', 'Михаил', 'Анатолий', 'Юлия', 'Алена', 'Настя'];
    private $patronyms = ['Борисович', 'Антонович', 'Макарович', 'Пётровна', 'Витальевна', 'Даниловна'];
    private $logins = ['pomidorkin', 'ptichkin', 'zmeev', 'aksenova', 'hohlova', 'selezneva'];

    public function run()
    {
        $hash_password = Hash::make(12345678);
        // Пароль: 12345678
        User::create([
            'login' => 'admin',
            'role_id' => 1,
            'password' => $hash_password,
            'name' => 'Админ',
            'surname' => 'Админов',
            'patronym' => 'Админыч',
        ]);
        for ($i = 0; $i < 3; ++$i) {
            User::create([
                'login' => $this->logins[$i],
                'role_id' => 2,
                'password' => $hash_password,
                'name' => $this->names[$i],
                'surname' => $this->surnames[$i],
                'patronym' => $this->patronyms[$i],
            ]);
        }
        for ($i = 3; $i < 6; ++$i) {
            User::create([
                'login' => $this->logins[$i],
                'role_id' => 3,
                'password' => $hash_password,
                'name' => $this->names[$i],
                'surname' => $this->surnames[$i],
                'patronym' => $this->patronyms[$i],
            ]);
        }
    }
}

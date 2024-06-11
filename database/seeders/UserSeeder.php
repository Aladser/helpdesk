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
            'name' => "admin",
            'role_id' => 1,
            'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',
        ]);
        for ($i = 1; $i < 4; ++$i) {
            User::create([
                'name' => "executor_$i",
                'role_id' => 2,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
            ]);
        }
        for ($i = 1; $i < 4; ++$i) {
            User::create([
                'name' => "client_$i",
                'role_id' => 3,
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
            ]);
        }
    }
}

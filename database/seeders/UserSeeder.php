<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i < 6; ++$i) {
            User::create([
                'name' => "user$i",
                'email' => "user$i@test.ru",
                'password' => '$2y$10$5lGrEwgHQ8/bv/KHPDeS8O8ZmxPBpKASM34Tni73DNrx9HzaNBOe2',//12345678
            ]);
        }
    }
}

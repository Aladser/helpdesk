<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    public function run()
    {
        $names = ['ready', 'process', 'non_ready'];
        $descriptions = ['Готов', 'В работе', 'Не готов'];

        for ($i = 0; $i < count($names); ++$i) {
            UserStatus::create(['name' => $names[$i], 'description' => $descriptions[$i]]);
        }
    }
}

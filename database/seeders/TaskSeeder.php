<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $current_datetime = Carbon::now();
        $current_datetime->subDay();

        for ($i = 0; $i < 4; ++$i) {
            for ($j = 5; $j < 8; ++$j) {
                Task::create([
                    'status_id' => 1,
                    'author_id' => $j,
                    'header' => 'Абзац 1.10.33 "de Finibus Bonorum et Malorum", написанный Цицероном в 45 году н.э. '.mt_rand(1000, 9999).'AA',
                    'content' => 'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации "Здесь ваш текст.. Здесь ваш текст.. Здесь ваш текст.." Многие программы электронной вёрстки и редакторы HTML используют Lorem Ipsum в качестве текста по умолчанию, так что поиск по ключевым словам "lorem ipsum" сразу показывает, как много веб-страниц всё ещё дожидаются своего настоящего рождения. За прошедшие годы текст Lorem Ipsum получил много версий. Некоторые версии появились по ошибке, некоторые - намеренно (например, юмористические варианты).',
                    'created_at' => $current_datetime,
                    'updated_at' => $current_datetime,
                ]);
                $current_datetime->addHour();
            }
        }
    }
}

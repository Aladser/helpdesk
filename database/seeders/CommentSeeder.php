<?php

namespace Database\Seeders;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    private string $text = 'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации.';

    public function run()
    {
        $current_datetime = Carbon::now();
        $current_datetime->subDay();
        $task_id = 2;

        for ($i = 0; $i < 3; ++$i) {
            Comment::create([
                'task_id' => $task_id,
                'author_id' => 3,
                'content' => $this->text,
                'created_at' => $current_datetime,
            ]);
            $current_datetime->addHour();
            Comment::create([
                'task_id' => $task_id,
                'author_id' => 6,
                'content' => $this->text,
                'created_at' => $current_datetime,
            ]);
            $current_datetime->addHour();
        }
    }
}

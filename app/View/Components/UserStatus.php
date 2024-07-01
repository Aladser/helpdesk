<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserStatus extends Component
{
    public $statuses;

    public function __construct()
    {
        $this->statuses = [];
        $class_names = ['text-green-400', 'text-orange-400', 'text-red-400'];
        $user_statuses = \App\Models\UserStatus::all();

        for ($i = 0; $i < count($class_names); ++$i) {
            $this->statuses[] = [
                'classname' => $class_names[$i],
                'name' => $user_statuses[$i]->name,
                'description' => $user_statuses[$i]->description,
            ];
        }
    }

    public function render()
    {
        return view('components.user-status');
    }
}

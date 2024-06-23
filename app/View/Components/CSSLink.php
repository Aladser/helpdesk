<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CSSLink extends Component
{
    public string $link;

    public function __construct(string $link)
    {
        $this->link = $link;
    }

    public function render()
    {
        return view('components.css-link');
    }
}

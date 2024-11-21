<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavigationButtons extends Component
{
    public $show;

    public function __construct($show = true)
    {
        $this->show = $show;
    }

    public function render()
    {
        return view('components.navigation-buttons');
    }
}
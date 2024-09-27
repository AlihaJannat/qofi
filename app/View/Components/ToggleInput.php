<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleInput extends Component
{
    public $id;
    public $name;
    public $value;
    public $label;

    public function __construct($id, $name, $value, $label)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
    }


    public function render()
    {
        return view('components.toggle-input');
    }
}

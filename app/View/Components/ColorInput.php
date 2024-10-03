<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ColorInput extends Component
{
    public $name;
    public $value;
    public $color;
    public $label;
    public $id;

    public function __construct($name, $id, $label, $value = null, $color = '#bdc1c2')
    {
        $this->name = $name;
        $this->value = $value;
        $this->color = $color;
        $this->id = $id;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.color-input');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckboxInput extends Component
{

    public $id;
    public $name;
    public $value = 0;
    public $label;
    /**
     * Create a new component instance.
     */

    public function __construct($id, $name, $value = 0, $label)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.checkbox-input');
    }
}

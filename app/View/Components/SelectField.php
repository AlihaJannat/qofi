<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectField extends Component
{
    public $label;
    public $id;
    public $className;
    public $name;
    public $showLabel;

    public function __construct($label='', $id='', $className='', $name='', $showLabel=false)
    {
        $this->label = $label;
        $this->id = $id;
        $this->className = $className;
        $this->name = $name;
        $this->showLabel = $showLabel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-field');
    }
}

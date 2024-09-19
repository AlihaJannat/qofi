<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PasswordInput extends Component
{
    public $label;
    public $id;
    public $value;
    public $type;
    public $placeholder;
    public $className;
    public $name;

    public function __construct($label='', $id='', $value='', $type='password', $placeholder='', $className='', $name='')
    {
        $this->label = $label;
        $this->id = $id;
        $this->value = $value;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->className = $className;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.password-input');
    }
}

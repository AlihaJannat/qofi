<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BreadCrumb extends Component
{
    public $previousLinks;
    public $currentPage;

    /**
     * Create a new component instance.
     */
    public function __construct($previousLinks, $currentPage)
    {
        $this->previousLinks = $previousLinks;
        $this->currentPage = $currentPage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bread-crumb');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SectionHeader extends Component
{
    public $topTitle;
    public $mainHeading;
    public $desc;
    public $btnText;
    public $link;

    /**
     * Create a new component instance.
     */
    public function __construct($topTitle, $mainHeading, $desc='', $btnText, $link)
    {
        $this->topTitle = $topTitle;
        $this->mainHeading = $mainHeading;
        $this->desc = $desc;
        $this->btnText = $btnText;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.section-header');
    }
}

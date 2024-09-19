<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MobileDropdownMenu extends Component
{
    public $label;
    public $submenuId;
    public $subCategories;
    public $products;
    public $categoryId;


    public function __construct($label, $submenuId, $subCategories=[], $products=[], $categoryId)
    {
        $this->label = $label;
        $this->submenuId = $submenuId;
        $this->subCategories = $subCategories;
        $this->products = $products;
        $this->categoryId = $categoryId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mobile-dropdown-menu');
    }
}

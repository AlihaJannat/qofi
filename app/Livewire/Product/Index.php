<?php

namespace App\Livewire\Product;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $minPrice = '10';
    #[Url]
    public $maxPrice = '80';
    #[Url]
    public $minHeight = '20';
    #[Url]
    public $maxHeight = '60';
    #[Url]
    public $sortOrderPrice = '';
    #[Url]
    public $selectedCategories = [];
    #[Url]
    public $selectedShops = [];
    #[Url]
    public $selectedCountries = [];
    #[Url]
    public $selectedColors = [];

    public $categories;
    public $shops;
    public $colors;
    public $minPriceValue;
    public $maxPriceValue;
    public $minHeightValue;
    public $maxHeightValue;
    public $countries;

    public $childComponentKey;

    public function mount()
    {
        $this->childComponentKey = uniqid();
        $this->minPrice = $this->minPriceValue;
        $this->maxPrice = $this->maxPriceValue;
        $this->minHeight = $this->minHeightValue;
        $this->maxHeight = $this->maxHeightValue;
    }

    public function reRenderChild()
    {
        $this->childComponentKey = uniqid();
    }

    public function render()
    {
        return view('livewire.product.index');
    }

    public function toggleColors($colorId)
    {
        if (in_array($colorId, $this->selectedColors)) {
            // Remove the color ID from the array
            $this->selectedColors = array_filter($this->selectedColors, function($id) use ($colorId) {
                return $id != $colorId;
            });
        } else {
            // Add the color ID to the array
            $this->selectedColors[] = $colorId;
        }
        $this->reRenderChild();
    }
}

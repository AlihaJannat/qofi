<?php

namespace App\Livewire\Product;

use App\Models\SwProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class Listing extends Component
{
    use WithPagination;

    public $minPrice;
    public $maxPrice;
    public $minHeight;
    public $maxHeight;
    public $sortOrderPrice;
    public $selectedCategories;
    public $selectedCountries;
    public $selectedShops;
    public $selectedColors;

    public function mount()
    {
        // $this->resetPage();
    }

    // #[On('reset-url-page')]
    // public function resetUrlPage()
    // {
    //     $this->page=1;
    // }

    public function render()
    {
        $query = SwProduct::with(['defaultHeight', 'shop:id,name,slug'])
            ->leftJoin('sw_user_wishlists', function ($join) {
                $join->on('sw_products.id', '=', 'sw_user_wishlists.sw_product_id')
                    ->where('sw_user_wishlists.user_id', '=', auth()->id());
            })
            ->where('sw_products.status', 1);

        // Filter by height
        if ($this->minHeight !== null) {
            $query->whereHas('defaultHeight', function ($query) {
                $query->where('value', '>=', $this->minHeight);
            });
        }

        if ($this->maxHeight !== null) {
            $query->whereHas('defaultHeight', function ($query) {
                $query->where('value', '<=', $this->maxHeight);
            });
        }

        // Filter by price within the defaultHeight relation
        if ($this->minPrice !== null) {
            $query->whereHas('defaultHeight', function ($query) {
                $query->where('price', '>=', $this->minPrice);
            });
        }

        if ($this->maxPrice !== null) {
            $query->whereHas('defaultHeight', function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            });
        }

        // Filter by selected categories
        if (count($this->selectedCategories)) {
            $query->whereIn('sw_category_id', $this->selectedCategories);
        }

        // Filter by selected countries
        if (count($this->selectedCountries)) {
            $query->whereIn('country_id', $this->selectedCountries);
        }

        // Filter by selected shops
        if (count($this->selectedShops)) {
            $query->whereIn('sw_shop_id', $this->selectedShops);
        }

        // Filter by selected colors
        if (count($this->selectedColors)) {
            $query->whereHas('colors', function ($query) {
                $query->whereIn('sw_colors.id', $this->selectedColors);
            });
        }

        // Apply sorting by price or latest
        if ($this->sortOrderPrice) {
            $query->leftJoin('sw_product_heights as default_heights', function ($join) {
                $join->on('sw_products.id', '=', 'default_heights.sw_product_id')
                    ->where('default_heights.is_default', '=', 1);
            })
                ->orderBy('default_heights.price', $this->sortOrderPrice)
                ->select('sw_products.*');
        } else {
            $query->latest();
        }
        $products = $query->select('sw_products.*',DB::raw('IF(sw_user_wishlists.sw_product_id IS NULL, 0, 1) AS in_wishlist'))->paginate(12);

        return view('livewire.product.listing', compact('products'));
    }

    public function placeholder()
    {
        return view('livewire.product.skeleton');
    }
}

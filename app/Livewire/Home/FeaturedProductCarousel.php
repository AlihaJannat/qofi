<?php

namespace App\Livewire\Home;

use App\Models\SwProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class FeaturedProductCarousel extends Component
{
    public $featuredProducts;

    public function mount()
    {
        $this->featuredProducts = SwProduct::with('defaultHeight', 'shop:id,name,slug')
            ->leftJoin('sw_user_wishlists', function ($join) {
                $join->on('sw_products.id', '=', 'sw_user_wishlists.sw_product_id')
                    ->where('sw_user_wishlists.user_id', '=', auth()->id());
            })
            ->take(20)
            ->where('status', 1)
            ->orderByDesc('featured')
            ->whereNotNull('featured')
            ->select('sw_products.*',DB::raw('IF(sw_user_wishlists.sw_product_id IS NULL, 0, 1) AS in_wishlist'))
            ->get();
    }

    public function render()
    {
        return view('livewire.home.featured-product-carousel');
    }

    public function test()
    {
        return null;
    }

    public function placeholder()
    {
        $this->dispatch('initializeFeaturedProductsCarousel');
        $id = 'featuredProductsCarousel';
        return view('livewire.home.product-card.skeleton', compact('id'));
    }

    public function rendered()
    {
        $this->dispatch('initializeFeaturedProductsCarousel');
    }
}

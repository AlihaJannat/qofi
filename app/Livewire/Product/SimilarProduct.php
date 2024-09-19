<?php

namespace App\Livewire\Product;

use App\Models\SwProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class SimilarProduct extends Component
{
    public $similarProducts;

    public function mount($category_id)
    {
        $this->similarProducts = SwProduct::with('shop:id,name,slug')
            ->leftJoin('sw_user_wishlists', function ($join) {
                $join->on('sw_products.id', '=', 'sw_user_wishlists.sw_product_id')
                    ->where('sw_user_wishlists.user_id', '=', auth()->id());
            })
            ->take(20)
            ->where('status', 1)
            ->where('sw_category_id', $category_id)
            ->select('sw_products.*',DB::raw('IF(sw_user_wishlists.sw_product_id IS NULL, 0, 1) AS in_wishlist'))
            ->get();
    }

    public function render()
    {
        $this->dispatch('initializeSimilarProductsCarousel');
        return view('livewire.product.similar-product');
    }

    public function placeholder()
    {
        $this->dispatch('initializeSimilarProductsCarousel');
        $id = 'similarProductsCarousel';
        return view('livewire.home.product-card.skeleton', compact('id'));
    }

    public function rendered()
    {
        $this->dispatch('initializeSimilarProductsCarousel');
    }
}

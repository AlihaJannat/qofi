<?php

namespace App\Livewire\Home;

use App\Models\SwProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class LatestProductCarousel extends Component
{
    public $latestProducts;

    public function mount()
    {
        $this->latestProducts = SwProduct::with('defaultHeight', 'shop:id,name,slug')
            ->leftJoin('sw_user_wishlists', function ($join) {
                $join->on('sw_products.id', '=', 'sw_user_wishlists.sw_product_id')
                    ->where('sw_user_wishlists.user_id', '=', auth()->id());
            })
            ->take(20)
            ->where('status', 1)
            ->orderByDesc('id')
            ->select('sw_products.*',DB::raw('IF(sw_user_wishlists.sw_product_id IS NULL, 0, 1) AS in_wishlist'))
            ->get();
    }

    public function render()
    {
        return view('livewire.home.latest-product-carousel', ['id' => 'latestProductsCarousel']);
    }

    public function test()
    {
        $this->skipMount();
        $this->skipRender();
        return null;
    }

    public function placeholder()
    {
        $this->dispatch('initializeLatestProductsCarousel');
        $id = 'latestProductsCarousel';
        return view('livewire.home.product-card.skeleton', compact('id'));
    }

    public function rendered()
    {
        $this->dispatch('initializeLatestProductsCarousel');
    }
}

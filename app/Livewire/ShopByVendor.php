<?php

namespace App\Livewire;

use App\Models\SwShop;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class ShopByVendor extends Component
{
    use WithPagination;

    public function render()
    {
        $shops = SwShop::with('owner:id,first_name,last_name,email', 'category:id,name')->paginate(12);
        return view('livewire.shop-by-vendor', compact('shops'));
    }

    public function placeholder()
    {
        $byVendor = true;
        return view('livewire.product.skeleton', compact('byVendor'));
    }
}

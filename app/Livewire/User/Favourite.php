<?php

namespace App\Livewire\User;

use App\Models\SwProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class Favourite extends Component
{
    use WithPagination;

    public function render()
    {
        $products = auth()->user()->wishlist()->paginate(6);

        return view('livewire.user.favourite', compact('products'));
    }

    public function removeProduct($product_id)
    {
        DB::table('sw_user_wishlists')->where('sw_product_id', $product_id)->where('user_id', auth()->id())->delete();
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="w-full h-52 pb-6 rounded-lg border !border-user-nav-pink-active flex flex-col items-center justify-center gap-6 relative">
                <div class="flex-1 text-black-5 text-2xl font-roboto font-bold leading-7 break-words w-full p-4">
                    My Favourites
                </div>
                <div class="absolute inset-0 bg-card-bg flex justify-center items-center z-10" id="loader-overlay">
                    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-12 w-12">
                    </div>
                </div>
            </div>
        HTML;
    }
}

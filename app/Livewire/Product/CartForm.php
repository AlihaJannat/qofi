<?php

namespace App\Livewire\Product;

use App\Models\SwDay;
use App\Models\SwProduct;
use App\Models\SwShopDay;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class CartForm extends Component
{
    #[Url]
    public $date;

    public $times = [];

    public SwProduct $product;

    public function mount(SwProduct $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        if ($this->date) {
            $this->getTime();
        }
        return view('livewire.product.cart-form');
    }

    public function getTime()
    {
        $date = Carbon::parse($this->date);
        $dayOfWeek = $date->format('l');
        if ($date <= today()) {
            session()->flash('error', 'Date must be greater than today');
            $this->times = [];
            return $this->date = null;
        }
        session()->flash('error', null);
        $day = SwDay::where('day', 'like', $dayOfWeek)->first();
        // dd($day);
        $shopDayTime = SwShopDay::with('times')->where('sw_day_id', $day->id)->where('sw_shop_id', $this->product->sw_shop_id)->first();
        if (!$shopDayTime || count($shopDayTime->times) == 0) {
            session()->flash('error', "Select date has no available time, try changing the date");
            $this->times = [];
            return;
        }

        $this->times = $shopDayTime->times;
    }
}

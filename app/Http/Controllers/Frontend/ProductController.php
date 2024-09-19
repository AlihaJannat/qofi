<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SwCategory;
use App\Models\SwColor;
use App\Models\SwProduct;
use App\Models\SwProductHeight;
use App\Models\SwShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index($shop_slug=null)
    {
        $shop = null;
        if ($shop_slug) {
            $shop = SwShop::where('slug', $shop_slug)->first();
            if (!$shop) {
                abort(404);
            }
        }
        
        $categories = SwCategory::withCount('products')->whereNull('parent_id')->where('status', 1)->get();
        $shops = [];
        if (!$shop) {
            $shops = SwShop::where('status', 1)->get(['id', 'slug', 'name']);
        }
        $colors = SwColor::get();
        $priceHeight = SwProduct::selectRaw('MIN(price) as min_price, MAX(price) as max_price, MIN(height) as min_height, MAX(height) as max_height')->first();
        $countries = DB::table('countries')->get(['id', 'name']);

        return view('frontend.products.index', compact('categories', 'colors', 'shops', 'priceHeight', 'countries', 'shop'));
    }

    public function detail(SwShop $shop, $slug)
    {
        $product = SwProduct::with('images')->where('slug', $slug)->where('sw_shop_id', $shop->id)->first();
        if (!$product) {
            abort(404);
        }
        // dd($product);
        
        return view('frontend.products.detail', compact('product'));
    }
}

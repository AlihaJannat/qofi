<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Http\Utils\CartUtils;
use App\Models\SwProduct;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public $cartUtils;

    public function __construct(CartUtils $cartUtils) {
        $this->cartUtils = $cartUtils;
    }

    public function add(CartAddRequest $request)
    {
        
        $validated = $request->validated();

        $product = SwProduct::with('shop:id,name,slug')->find($validated['product_id']);

        $product->shop['delivery_type'] = '';
        $product->shop['amount_limit'] = 0;
        $product->shop['delivery_charges'] = 0;

        $validated['name'] = $product->name;
        $validated['price'] = $product->getPrice();
        $validated['image_name'] = $product->image_name;
        $validated['slug'] = $product->slug;
        $validated['shop_slug'] = $product->shop?->slug;
        if($product->shop->deliverycharges){
            $product->shop['delivery_type'] = $product->shop->deliverycharges->delivery_charge_type;
            $product->shop['amount_limit'] = $product->shop->deliverycharges->limit;
            $product->shop['delivery_charges'] = $product->shop->deliverycharges->max_amount;
        }
        $validated['shop'] = $product->shop;
        
        // Add to cart
        $this->cartUtils->storeInSession($validated);
        
        return response()->json(['message' => 'Product added to cart successfully!', 'cart' => getCart()]);
    }
}

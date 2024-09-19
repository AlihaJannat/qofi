<?php

namespace App\Http\Utils;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartUtils
{
    public function storeInSession(array $validatedData)
    {
        // Retrieve the current cart from the session or create a new one
        $cart = Session::get('cart', []);
    
        // Generate a unique UUID for the cart item
        $cartItemId = (string) Str::uuid();
    
        // Prepare the cart item
        $cartItem = [
            'product_id' => $validatedData['product_id'],
            'quantity' => $validatedData['quantity'] ?? 1,
            'date' => $validatedData['date'] ?? null,
            'time' => $validatedData['time'] ?? null,
            'personal_message' => $validatedData['personal_message'] ?? null,
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'image_name' => $validatedData['image_name'] ?? '',
            'slug' => $validatedData['slug'],
            'shop_slug' => $validatedData['shop_slug'],
            'shop' => $validatedData['shop'],
        ];
    
        // Check if the shop_slug already exists in the cart
        if (!isset($cart[$validatedData['shop_slug']])) {
            $cart[$validatedData['shop_slug']] = [];
        }
    
        // Add the new item to the shop-specific cart
        $cart[$validatedData['shop_slug']][$cartItemId] = $cartItem;
    
        // Store the updated cart back in the session
        Session::put('cart', $cart);
    }
}

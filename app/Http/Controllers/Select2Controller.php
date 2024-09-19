<?php

namespace App\Http\Controllers;

use App\Models\SwProduct;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    public function products(Request $request)
    {
        $perPage = 20;
        $query = SwProduct::query()->where('status', 1);
        if ($request->shop_id) {
            $query = $query->where('sw_shop_id', $request->shop_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
            $products = $query->paginate($perPage);
        } else {
            $products = $query->paginate($perPage);
        }


        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage()
        ]);
    }
}

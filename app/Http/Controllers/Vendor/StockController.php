<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwProductHeight;
use App\Models\SwStockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index($height_id)
    {
        $height = SwProductHeight::with('product:id,name,sw_shop_id', 'unit:id,unit')->findOrFail($height_id);
        if (!$height->product || ($height->product->sw_shop_id != auth('vendor')->user()->sw_shop_id)) {
            abort(404);
        }

        return view('vendor.product.height.stock', get_defined_vars());
    }

    public function add(Request $request, $height_id)
    {
        $validated = $request->validate([
            'qty' => 'required|numeric',
            'type' => 'required|in:in,out',
            'description' => 'required|max:255',
        ]);
        $validated['sw_product_height_id'] = $height_id;

        $height = SwProductHeight::find($height_id);
        if (!$height) {
            return response()->json(['message' => 'not found'], 400);
        }

        if ($validated['type'] == 'in') {
            $height->stock = $height->stock+$request->qty;
        } else {
            if ($height->stock < $request->qty) {
                return response()->json(['message' => 'Cannot decrease more than existing stock'], 400);
            }
            $height->stock = $height->stock-$request->qty;
        }
        SwStockHistory::create($validated);
        $height->save();

        return response()->json('done');
    }
}

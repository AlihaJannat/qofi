<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwProduct;
use App\Models\SwProductHeight;
use App\Models\SwUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductHeightController extends Controller
{
    public function index($product_id)
    {
        $product = SwProduct::with('heights.unit')
            ->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->findOrFail($product_id);

        $units = SwUnit::where('name', 'like', 'height')->get();

        return view('vendor.product.height.index', compact('product', 'units'));
    }

    public function add(Request $request, $product_id)
    {
        $validated = $request->validate([
            'value' => 'required|numeric',
            'price' => 'required|numeric',
            'sw_unit_id' => 'required|exists:sw_units,id',
            'is_default' => 'required|in:0,1',
        ]);
        $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->findOrFail($product_id);
        if ($request->is_default == 1) {
            DB::table('sw_product_heights')->where('sw_product_id', $product->id)->update([
                'is_default' => 0
            ]);
        }
        $validated['sw_product_id'] = $product->id;
        SwProductHeight::create($validated);

        return to_route('vendor.product.height.index', $product_id)->with('status', "Height Added!");
    }

    public function update(Request $request, $product_id)
    {
        $validated = $request->validate([
            'id' => 'required',
            'value' => 'required|numeric',
            'price' => 'required|numeric',
            'sw_unit_id' => 'required|exists:sw_units,id',
            'is_default' => 'required|in:0,1',
        ]);
        $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->findOrFail($product_id);
        if ($request->is_default == 1) {
            DB::table('sw_product_heights')->where('sw_product_id', $product->id)->update([
                'is_default' => 0
            ]);
        }
        $validated['sw_product_id'] = $product->id;
        SwProductHeight::where('id', $request->id)->update($validated);

        return to_route('vendor.product.height.index', $product_id)->with('status', "Height Added!");
    }

    public function status(Request $request, $product_id)
    {
        $product = SwProduct::withCount('heights')
            ->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->find($product_id);
            
        if (!$product) {
            return response()->json(['message' => 'Product not found'],400);
        }

        SwProductHeight::where('sw_product_id', $product->id)->where('id', $request->id)->update([
            'status' => $request->status
        ]);

        return response()->json('done');
    }

    public function delete(Request $request, $product_id)
    {
        $product = SwProduct::withCount('heights')
            ->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->find($product_id);
            
        if (!$product) {
            return response()->json(['message' => 'Product not found'],400);
        }
        if ($product->heights_count <= 1) {
            return response()->json(['message' => "At least one height varient is mendatory"],400);
        }

        SwProductHeight::where('sw_product_id', $product->id)->where('id', $request->id)->delete();

        return response()->json('done');
    }
}

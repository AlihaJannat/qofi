<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('vendor.coupon.index');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:255',
            'value' => 'required|numeric',
            'type' => 'required|in:percent,fixed',
            'max_limit' => 'nullable|numeric',
            'expired_at' => 'required|date',
        ]);

        $existing = SwCoupon::where('code', $request->code)->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->first();
        if ($existing) {
            return response()->json(['message' => "Coupon Code Already exists"], 400);
        }

        $validated['use_count'] = 0;
        $validated['sw_shop_id'] = auth('vendor')->user()->sw_shop_id;

        SwCoupon::create($validated);

        return response()->json('done');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'code' => 'required|max:255',
            'value' => 'required|numeric',
            'type' => 'required|in:percent,fixed',
            'max_limit' => 'nullable|numeric',
            'expired_at' => 'required|date',
        ]);

        $coupon = SwCoupon::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->find($request->id);
        if (!$coupon) {
            return response()->json(['message' => "Coupon not found"]);
        }

        $existing = SwCoupon::where('code', $request->code)->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->where('id', '<>', $coupon->id)->first();
        if ($existing) {
            return response()->json(['message' => "Coupon Code Already exists"], 400);
        }

        $coupon->update($validated);

        return response()->json('done');
    }

    public function changeStatus(Request $request)
    {
        $coupon = SwCoupon::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->find($request->id);
        $coupon->status = $request->status;
        $coupon->save();

        return response()->json('done');
    }

    public function delete(Request $request)
    {
        $coupon = SwCoupon::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->find($request->id);
        $coupon->delete();

        return response()->json('done');
    }
}

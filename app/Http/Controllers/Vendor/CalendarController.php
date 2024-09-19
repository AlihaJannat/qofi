<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwDay;
use App\Models\SwShopDay;
use App\Models\SwTime;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function days()
    {
        $shopDays = SwShopDay::with('day', 'times')->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->get();
        $existingIds = $shopDays->pluck('sw_day_id')->toArray();

        $days = SwDay::whereNotIn('id', $existingIds)->where('status', 1)->get();

        $times = SwTime::where('status', 1)->get();

        return view('vendor.calendar.days', compact('days', 'shopDays', 'times'));
    }

    public function dayAdd(Request $request)
    {
        $request->validate([
            'sw_day_id' => 'required', 
            'sw_time_id' => 'required|array', 
            'sw_time_id.*' => 'required', 
        ]);

        $shopDay = SwShopDay::create([
            'sw_day_id' => $request->sw_day_id,
            'sw_shop_id' => auth('vendor')->user()->sw_shop_id,
        ]);

        $shopDay->times()->sync($request->sw_time_id, true);

        return to_route('vendor.calendar.day');
    }

    public function dayEdit(Request $request)
    {
        $request->validate([
            'id' => 'required', 
            'sw_time_id' => 'required|array', 
            'sw_time_id.*' => 'required', 
        ]);

        $shopDay = SwShopDay::findorfail($request->id);
        $shopDay->times()->sync($request->sw_time_id, true);

        return to_route('vendor.calendar.day');
    }

    public function dayDelete(Request $request)
    {
        SwShopDay::destroy($request->id);

        return response()->json('done');
    }

    public function dayStatus(Request $request)
    {
        SwShopDay::where('id', $request->id)->update(['status' => $request->status]);

        return response()->json('done');
    }

    public function times()
    {
        $times = SwTime::get();

        return view('vendor.calendar.times', compact('times'));
    }

    public function timeStatus(Request $request)
    {
        SwTime::where('id', $request->id)->update(['status' => $request->status]);

        return response()->json('done');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwDay;
use App\Models\SwTime;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function days()
    {
        $days = SwDay::get();

        return view('admin.calendar.days', compact('days'));
    }

    public function dayStatus(Request $request)
    {
        SwDay::where('id', $request->id)->update(['status' => $request->status]);

        return response()->json('done');
    }

    public function times()
    {
        $times = SwTime::get();

        return view('admin.calendar.times', compact('times'));
    }

    public function timeStatus(Request $request)
    {
        SwTime::where('id', $request->id)->update(['status' => $request->status]);

        return response()->json('done');
    }
}

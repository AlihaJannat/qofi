<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getStates(Request $request)
    {        
        if ($request->ajax()) {
            $states = DB::table('states')->where('country_id', $request->state_id)->where('status', 1)->select('id', 'name')->get();
            return response()->json(['states' => $states], 200);
        }
        $states = DB::table('states')->where('state_id', $request->state_id)->select('id', 'name', 'flag', 'status')->get();

        return view('adminpages.locations.city', compact('states', 'state'));
    }

    public function getCities(Request $request)
    {
        $state = State::findorfail($request->state_id);
        
        if ($request->ajax()) {
            $cities = DB::table('cities')->where('state_id', $request->state_id)->where('status', 1)->select('id', 'name')->get();
            return response()->json(['cities' => $cities], 200);
        }
        $cities = DB::table('cities')->where('state_id', $request->state_id)->select('id', 'name', 'flag', 'status')->get();

        return view('adminpages.locations.city', compact('cities', 'state'));
    }
}

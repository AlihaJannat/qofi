<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwShop;
use App\Models\SwVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $vendor = SwVendor::where('email', $request->email)->first();
            if (!$vendor || $vendor->status == 0) {
                return response()->json(['error' => 'Login Failure'], 400);
            }

            $credentials = $request->only('email', 'password');
            if (Auth::guard('vendor')->attempt($credentials)) {
                return response()->json(['message' => 'Vendor Login Success'], 200);
            }

            return response()->json(['error' => 'Login Failure'], 400);
        } else {
            if (auth()->guard('vendor')->check()) {
                if (auth()->guard('vendor')->user()->role == 'vendor') {
                    return redirect()->route('vendor.dash');
                } else {
                    return view('vendor.login')->with('error', "Your are not logged in as Vendor");
                }
            }
            return view('vendor.login');
        }
    }

    public function logout()
    {
        if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->role === 'vendor') {
            Auth::guard('vendor')->logout();
            return redirect()->route('vendor.login');
        }
    }

    public function dashboard()
    {
        return view('vendor.dashboard', get_defined_vars());
    }

    public function changeCurrentShop($shop_id)
    {
        $vendor = auth('vendor')->user();
        // checking if given shop is of current vendor
        $shop = SwShop::where('owner_id', $vendor->id)->find($shop_id);
        if ($shop) {
            $vendor->sw_shop_id = $shop_id;
            $vendor->save();
        }

        return redirect()->route('vendor.dash');
    }
}

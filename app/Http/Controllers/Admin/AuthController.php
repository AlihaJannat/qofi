<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $user = User::where('email', $request->email)->where('role', 'admin')->first();
            if (!$user || $user->status == 0) {
                return response()->json(['error' => 'Login Failure'], 400);
            }

            $credentials = $request->only('email', 'password');
            if (Auth::guard('admin')->attempt($credentials)) {
                return response()->json(['message' => 'Admin Login Success'], 200);
            }

            return response()->json(['error' => 'Login Failure'], 400);
        } else {
            if (auth()->guard('admin')->check()) {
                if (auth()->guard('admin')->user()->role == 'admin') {
                    return redirect()->route('admin.dash');
                } else {
                    return view('admin.login')->with('error', "Your are not logged in as Admin");
                }
            }
            return view('admin.login');
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }
    }

    public function dashboard()
    {
        return view('admin.dashboard', get_defined_vars());
    }
}

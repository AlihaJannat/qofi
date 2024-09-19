<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();
        $roles = DB::table('sw_roles')
            ->where('module', 'vendor')
            ->where('sw_shop_id', $vendor->sw_shop_id)
            ->get();
        
        return view('vendor.user.index',compact('roles', 'vendor'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'email' => [
                'required',
                'max:100',
                'email',
                Rule::unique('sw_vendors', 'email'),
            ],
            'phone_no' => [
                'required',
                'max:100',
                'regex:/^[4569]/',
                Rule::unique('sw_vendors', 'phone_no'),
            ],
            'sw_role_id' => 'required',
            'password' => 'required|max:100',
        ]);

        SwVendor::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'sw_role_id' => $request->sw_role_id,
            'sw_shop_id' => auth('vendor')->user()->sw_shop_id,
            'password' => Hash::make($request->password),
        ]);

        return response()->json('done');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sw_vendors,id',
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'email' => [
                'required',
                'max:100',
                'email',
                Rule::unique('sw_vendors', 'email')->ignore($request->id),
            ],
            'phone_no' => [
                'required',
                'max:100',
                'regex:/^[4569]/',
                Rule::unique('sw_vendors', 'phone_no')->ignore($request->id),
            ],
            'sw_role_id' => 'required',
            'password' => 'nullable|max:100',
        ]);

        $owner = SwVendor::find($request->id);
        $owner->first_name = $request->first_name;
        $owner->last_name = $request->last_name;
        $owner->email = $request->email;
        $owner->phone_no = $request->phone_no;
        $owner->sw_role_id = $request->sw_role_id;
        $owner->sw_shop_id = auth('vendor')->user()->sw_shop_id;
        if ($request->password) {
            $owner->password = Hash::make($request->password);
        }
        $owner->save();

        return response()->json('done');
    }

    public function changeStatus(Request $request)
    {
        $owner = SwVendor::find($request->id);
        $owner->status = $request->status;
        $owner->save();
        return response()->json("done");
    }

    public function delete(Request $request)
    {
        SwVendor::destroy($request->id);
        return response()->json("done");
    }
}

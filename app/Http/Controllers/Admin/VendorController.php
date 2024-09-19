<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.vendor.index');
    }

    public function add(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'dob' => 'nullable|date',
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
            'password' => 'required|max:100',
        ]);

        SwVendor::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'dob' => $request->dob,
            'role' => "owner",
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
            'dob' => 'nullable|date',
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
            'password' => 'nullable|max:100',
        ]);

        $owner = SwVendor::find($request->id);
        $owner->first_name = $request->first_name;
        $owner->last_name = $request->last_name;
        $owner->email = $request->email;
        $owner->dob = $request->dob;
        $owner->phone_no = $request->phone_no;
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

<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwPermission;
use App\Models\SwRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = SwPermission::where('role_module', 'like', 'vendor')->get(['id', 'name', 'module'])->groupBy('module');
        // dd($permissions);
        return view("vendor.role.index", get_defined_vars());
    }

    public function delete(Request $request)
    {
        SwRole::where("id", $request->id)->where('module', 'like', 'user')->delete();
        return response()->json('done');
    }

    public function getDetails(Request $request)
    {
        $role = SwRole::where('module', 'like', 'vendor')->findorfail($request->id);
        $permissions = SwPermission::where('role_module', 'like', 'vendor')->get(['id', 'name', 'module'])->groupBy('module');

        $html = view('vendor.role.editRender', get_defined_vars())->render();
        return response()->json(['html' => $html]);
    }
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'permissions' => 'required|array'
        ]);
        $check = SwRole::where("name", 'like', $request->name)
        ->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
        ->where('module', 'like', 'vendor')->first();
        if (!empty($check)) {
            return response()->json(['message' => "Role already registered"], 400);
        }
        // dd($request->all());
        $role = new SwRole;
        $role->name = $request->name;
        $role->module = "vendor";
        $role->sw_shop_id = auth('vendor')->user()->sw_shop_id;
        $role->save();
        $role->permissions()->sync($request->permissions, true);
        return response()->json(['message' => "Role successfully registered!"]);
    }
    public function edit(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'name' => 'required|max:255',
            'permissions' => 'required|array'
        ]);
        $check = SwRole::where("name", 'like', $request->name)
            ->where('module', 'like', 'vendor')
            ->where('sw_shop_id', auth('vendor')->user()->sw_shop_id)
            ->where('id', '!=', $request->role_id)->first();
        if (!empty($check)) {
            return response()->json(['message' => "Role already registered"], 400);
        }
        $role = SwRole::findorfail($request->role_id);
        $role->name = $request->name;
        $role->save();
        $role->permissions()->sync($request->permissions, true);
        return response()->json(['message' => "Role successfully updated!"]);
    }
}

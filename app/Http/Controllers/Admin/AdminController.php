<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $roles = DB::table('sw_roles')->where('module', 'like', 'user')->get();
        
        return view('admin.adminuser.index', compact('roles'));
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
                Rule::unique('users', 'email'),
            ],
            'sw_role_id' => 'required',
            'password' => 'required|max:100',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => "admin",
            'sw_role_id' => $request->sw_role_id,
            'password' => Hash::make($request->password),
        ]);

        return response()->json('done');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'email' => [
                'required',
                'max:100',
                'email',
                Rule::unique('users', 'email')->ignore($request->id),
            ],
            'sw_role_id' => 'required',
            'password' => 'nullable|max:100',
        ]);

        $owner = User::find($request->id);
        $owner->first_name = $request->first_name;
        $owner->last_name = $request->last_name;
        $owner->email = $request->email;
        $owner->sw_role_id = $request->sw_role_id;
        if ($request->password) {
            $owner->password = Hash::make($request->password);
        }
        $owner->save();

        return response()->json('done');
    }

    public function changeStatus(Request $request)
    {
        $owner = User::find($request->id);
        $owner->status = $request->status;
        $owner->save();
        return response()->json("done");
    }

    public function delete(Request $request)
    {
        User::destroy($request->id);
        return response()->json("done");
    }
}

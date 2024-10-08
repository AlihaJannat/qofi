<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SwProductAddon;
use Illuminate\Http\Request;

class ProductAddonController extends Controller
{

    public function index()
    {
        return view('admin.productaddon.index');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
        ]);
        $validated['has_price'] = ($request->has('has_price') ? 1 : 0);
        $validated['status'] = 1;

        SwProductAddon::create($validated);

        return response()->json(['success' => true], 200);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|max:255',
        ]);

        $validated['has_price'] = ($request->has('has_price') ? 1 : 0);
        SwProductAddon::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true], 200);
    }

    public function delete(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->productoriginIds)) {
                SwProductAddon::whereIn('id', $request->productoriginIds)->delete();
            }

            return response('done');
        }
        SwProductAddon::destroy($request->id);
    }

    public function changeStatus(Request $request)
    {
        $subscription = SwProductAddon::findorfail($request->id);
        $subscription->status = $request->status;
        $subscription->save();

        return response()->json('done');
    }
}

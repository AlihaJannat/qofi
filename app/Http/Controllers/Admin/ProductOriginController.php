<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SwProductOrigin;
use Illuminate\Http\Request;

class ProductOriginController extends Controller
{
    public function index()
    {
        return view('admin.productorigin.index');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
        ]);
        $validated['slug'] = createSlug($request->title);
        $validated['status'] = 1;

        SwProductOrigin::create($validated);

        return response()->json(['success' => true], 200);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|max:255',
        ]);

        SwProductorigin::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true], 200);
    }

    public function delete(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->productoriginIds)) {
                SwProductorigin::whereIn('id', $request->productoriginIds)->delete();
            }

            return response('done');
        }
        SwProductorigin::destroy($request->id);
    }

    public function changeStatus(Request $request)
    {
        $subscription = SwProductOrigin::findorfail($request->id);
        $subscription->status = $request->status;
        $subscription->save();

        return response()->json('done');
    }
}

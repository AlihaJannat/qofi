<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeightController extends Controller
{
    public function index()
    {
        return view('admin.height.index');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required',
        ]);
        $validated['name'] = 'height';

        SwUnit::create($validated);

        return response()->json(['success' => true],200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'unit' => 'required|max:255',
        ]);

        SwUnit::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true],200);
    }

    public function delete(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->heightIds)) {
                SwUnit::whereIn('id', $request->heightIds)->delete();
            }

            return response('done');
        }
        SwUnit::destroy($request->id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function index()
    {
        return view('admin.colors.view');
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'hex_code' => 'required|max:255',
        ]);

        SwColor::create($validated);

        return response()->json(['success' => true],200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:sw_colors',
            'name' => 'required|max:255',
            'hex_code' => 'required|max:255',
        ]);

        SwColor::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true],200);
    }

    public function delete(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->colorIds)) {
                DB::table('sw_colors')->whereIn('id', $request->colorIds)->delete();
            }

            return response('done');
        }
        SwColor::destroy($request->id);
    }
}

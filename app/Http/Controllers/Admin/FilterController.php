<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwFilter;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index()
    {
        return view('admin.filter.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            ]);
            $filter = new SwFilter();
            $filter->image = "/filter/" . upload_image($request->file('image'), 'images/filter');
            $filter->name = $request->name;
            $filter->sort_order = $request->sort_order;
            $filter->save();
            return redirect()->route('admin.filter.new')->with('status', "Filter Added SuccessFully");
        } else {
            return view('admin.filter.new');
        }
    }

    public function edit(Request $request, $id)
    {
        $filter = SwFilter::findorfail($id);
        if ($request->isMethod('POST')) {
            $request->validate([
            'name' => 'required|string',


            ]);
            $filter->name = $request->name;
            $filter->sort_order = $request->sort_order;
            $filter->save();

            return to_route('admin.filter.edit', $filter->id)->with('status', "Filter Edited!");
        }
        return view('admin.filter.edit', compact('filter'));
    }

    public function imageChange(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);
        $filter = SwFilter::findorfail($request->id);
        if ($request->file('image')) {
            $fileName = upload_image($request->file('image'), '/images/filter');
            delete_image($filter->image, 'images/');
            $filter->image = "/filter/" . $fileName;
            $filter->save();
            return redirect()->back();
        }
        return response()->json([
            "error" => "something went wrong please try letter"
        ], 400);
    }


    public function delete(Request $request)
    {
        SwFilter::destroy($request->id);

    }

    public function changeStatus(Request $request)
    {
        $filter = SwFilter::find($request->id);
        $filter->status = $request->status;
        $filter->save();
        return response()->json("done");
    }
}

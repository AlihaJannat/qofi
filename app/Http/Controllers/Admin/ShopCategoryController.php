<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwShopCategory;
use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    public function index()
    {
        return view('admin.shop.category.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
            ]);
            $category = new SwShopCategory;
            $category->name = $request->name;
            $category->image_name = "/shop-categories/" . upload_image($request->file('image'), 'images/shop-categories');
            $category->save();
            return redirect()->route('admin.shop.category.new')->with('status', "Category Added SuccessFully");
        } else {
            return view('admin.shop.category.new');
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:3072',
            ]);
            $category = SwShopCategory::findorfail($id);
            $category->name = $request->name;
            if ($request->file('image')) {
                $fileName = upload_image($request->file('image'), '/images/shop-categories');
                delete_image($category->image_name,'images');
                $category->image_name = "/shop-categories/" . $fileName;
            }
            $category->save();
            return redirect()->route('admin.shop.category.edit', ['id' => $category->id])->with('status', "Category Edited SuccessFully");
        } else {
            $category = SwShopCategory::findorfail($id);
            return view('admin.shop.category.edit', compact('category'));
        }
    }

    public function imageChange(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:3072',
        ]);
        $category = SwShopCategory::findorfail($request->id);
        if ($request->file('image')) {
            $fileName = upload_image($request->file('image'), '/images/shop-categories');
            delete_image($category->image_name,'images');
            $category->image_name = "/shop-categories/" . $fileName;
            $category->save();
            return redirect()->back();
        }
        return response()->json([
            "error" => "something went wrong please try letter"
        ], 400);
    }


    public function delete(Request $request)
    {
        $category = SwShopCategory::withCount('shops')->find($request->id);
        if ($category->shops_count) {
            return response()->json(['message' => "category has shops"],422);
        }
        delete_image($category->image_name,'images');
        $category->delete();
    }

    public function changeStatus(Request $request)
    {
        $category = SwShopCategory::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json("done");
    }
}

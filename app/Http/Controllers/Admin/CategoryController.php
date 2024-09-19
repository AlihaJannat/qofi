<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'parent_id' => 'nullable|exists:sw_categories,id',
                'show_nav' => 'required|in:0,1',
                'name' => 'required|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
            ]);
            $category = new SwCategory;
            $category->name = $request->name;
            $category->image_name = "/categories/" . upload_image($request->file('image'), 'images/categories');
            $category->parent_id = $request->parent_id;
            if (!$request->parent_id) {
                $category->show_nav = $request->show_nav;
            }
            $category->save();
            return redirect()->route('admin.category.new')->with('status', "Category Added SuccessFully");
        } else {
            $categories = SwCategory::whereNull('parent_id')->get(['id', 'name']);
            return view('admin.category.new', compact('categories'));
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'parent_id' => 'nullable|exists:sw_categories,id',
                'name' => 'required|max:255',
                'show_nav' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:3072',
            ]);
            $category = SwCategory::findorfail($id);
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            if ($request->file('image')) {
                $fileName = upload_image($request->file('image'), '/images/categories');
                delete_image($category->image_name,'images');
                $category->image_name = "/categories/" . $fileName;
            }
            if (!$request->parent_id) {
                $category->show_nav = $request->show_nav;
            }
            $category->save();
            return redirect()->route('admin.category.edit', ['id' => $category->id])->with('status', "Category Edited SuccessFully");
        } else {
            $category = SwCategory::findorfail($id);
            $categories = SwCategory::whereNull('parent_id')->get(['id', 'name']);
            return view('admin.category.edit', compact('category', 'categories'));
        }
    }
    
    public function delete(Request $request)
    {
        $category = SwCategory::find($request->id);
        
        $category->delete();
    }

    public function changeStatus(Request $request)
    {
        $category = SwCategory::find($request->id);
        $category->status = $request->status;
        $category->save();
        return response()->json("done");
    }

    public function getChildCategories(Request $request)
    {
        $categories = SwCategory::where('parent_id', $request->id)->get(['id', 'name']);

        return response()->json(['categories' => $categories]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwBanner;
use App\Rules\SvgFile;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banner.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'image_name' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
                'type' => 'required|in:simple,promotional',
                'url' => 'nullable|max:255',
                'banner_text' => 'nullable|max:255',
                'sort_order' => 'nullable|numeric',
                
            ]);
            $banner = new SwBanner();
            $banner->image_name = "/banner/" . upload_image($request->file('image_name'), 'images/banner');
            $banner->page = 'home';
            $banner->type = $request->type;
            $banner->banner_text = $request->banner_text;
            $banner->url = $request->url;
            $banner->sort_order = $request->sort_order;
            $banner->save();
            return redirect()->route('admin.banner.new')->with('status', "banner Added SuccessFully");
        } else {
            return view('admin.banner.new');
        }
    }

    public function edit(Request $request, $id)
    {
        $banner = SwBanner::findorfail($id);
        if ($request->isMethod('POST')) {
            $request->validate([
                'type' => 'required|in:simple,promotional',
                'url' => 'nullable|max:255',
                'banner_text' => 'nullable|max:255',
                'sort_order' => 'nullable|numeric',
                
            ]);
            $banner->page = 'home';
            $banner->type = $request->type;
            $banner->banner_text = $request->banner_text;
            $banner->url = $request->url;
            $banner->sort_order = $request->sort_order;
            $banner->save();

            return to_route('admin.banner.edit', $banner->id)->with('status', "Banner Edited!");
        }
        return view('admin.banner.edit', compact('banner'));
    }

    public function imageChange(Request $request)
    {
        $request->validate([
            'image_name' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);
        $banner = SwBanner::findorfail($request->id);
        if ($request->file('image_name')) {
            $fileName = upload_image($request->file('image_name'), '/images/banner');
            delete_image($banner->image_name, 'images/');
            $banner->image_name = "/banner/" . $fileName;
            $banner->save();
            return redirect()->back();
        }
        return response()->json([
            "error" => "something went wrong please try letter"
        ], 400);
    }


    public function delete(Request $request)
    {
        SwBanner::destroy($request->id);
        
    }

    public function changeStatus(Request $request)
    {
        $banner = SwBanner::find($request->id);
        $banner->status = $request->status;
        $banner->save();
        return response()->json("done");
    }
}

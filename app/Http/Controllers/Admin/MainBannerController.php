<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SwMainBanner;
use App\Rules\SvgFile;
class MainBannerController extends Controller
{
    public function index()
    {
        return view('admin.main_banner.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'has_button' => 'required|boolean',
            'button_color' => 'nullable|string|max:255',
            'button_bg_color' => 'nullable|string|max:255',
            'button_text' => 'nullable|string',
            'sort_order' => 'required|integer'

            ]);
            $banner = new SwMainBanner();
            $banner->image = "/main_banner/" . upload_image($request->file('image'), 'images/main_banner');
            $banner->title = $request->title;
            $banner->has_button = $request->has_button;
            if ($request->has_button) {
                $banner->button_color = $request->button_color;
                $banner->button_bg_color = $request->button_bg_color;
                $banner->button_text = $request->button_text;
            } else {
                $banner->button_color = null;
                $banner->button_bg_color = null;
                $banner->button_text = null;
            }
            $banner->sort_order = $request->sort_order;
            $banner->save();
            return redirect()->route('admin.main-banner.new')->with('status', "banner Added SuccessFully");
        } else {
            return view('admin.main_banner.new');
        }
    }

    public function edit(Request $request, $id)
    {
        $banner = SwMainBanner::findorfail($id);
        if ($request->isMethod('POST')) {
            $request->validate([
                'title' => 'required|string',
                'has_button' => 'required|boolean',
                'button_color' => 'nullable|string|max:255',
                'button_bg_color' => 'nullable|string|max:255',
                'button_text' => 'nullable|string',
                'sort_order' => 'required|integer'

            ]);
            $banner->title = $request->title;
            $banner->has_button = $request->has_button;
                if ($request->has_button) {
                    $banner->button_color = $request->button_color;
                    $banner->button_bg_color = $request->button_bg_color;
                    $banner->button_text = $request->button_text;
                } else {
                    $banner->button_color = null;
                    $banner->button_bg_color = null;
                    $banner->button_text = null;
                }
            $banner->sort_order = $request->sort_order;
            $banner->save();

            return to_route('admin.main-banner.edit', $banner->id)->with('status', "Banner Edited!");
        }
        return view('admin.main_banner.edit', compact('banner'));
    }

    public function imageChange(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);
        $banner = SwMainBanner::findorfail($request->id);
        if ($request->file('image')) {
            $fileName = upload_image($request->file('image_name'), '/images/main_banner');
            delete_image($banner->image_name, 'images/');
            $banner->image_name = "/main_banner/" . $fileName;
            $banner->save();
            return redirect()->back();
        }
        return response()->json([
            "error" => "something went wrong please try letter"
        ], 400);
    }


    public function delete(Request $request)
    {
        SwMainBanner::destroy($request->id);

    }

    public function changeStatus(Request $request)
    {
        $banner = SwMainBanner::find($request->id);
        $banner->status = $request->status;
        $banner->save();
        return response()->json("done");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwAppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function getSettings(Request $request)
    {
        if ($request->isMethod('POST')) {
            $settings = $request->except('_token');
            foreach ($settings as $key => $value) {
                $set = SwAppSetting::where('key', $key)->first() ?: new SwAppSetting();
                $set->key = $key;
                $set->value = $value;
                $set->save();
            }

            \Cache::forget('app_settings');
            return redirect()->route('admin.app.setting');
        }

        $images = [];
        $imagePaths = glob(public_path('assets/images/admin/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF,ico,svg}'), GLOB_BRACE);
        foreach ($imagePaths as $path) {
            $images[] = (object) [
                'url' => asset(str_replace(public_path(), '', $path)),
                'name' => basename($path)
            ];
        }
        return view('admin.settings.index', compact('images'));
    }

    public function addImg(Request $request)
    {
        $fileName = upload_image($request->file('image'), '/assets/images/admin');
        $filePath = asset('assets/images/admin/' . $fileName);

        return response()->json(['imgUrl' => $filePath, 'imgName' => $fileName]);
    }

    public function deleteImg(Request $request)
    {
        delete_image($request->imageName, '/assets/images/admin/');

        return response()->json("done");
    }
}

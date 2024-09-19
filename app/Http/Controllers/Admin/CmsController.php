<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwCms;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function get($page_name)
    {
        $page = SwCms::where('page', $page_name)->first();
        if (!$page) {
            abort(404);
        }
        
        return view('admin.cms.index', compact('page'));
    }

    public function edit(Request $request, SwCms $page)
    {
        $page->content = $request->content;
        $page->save();

        return to_route('admin.cms.get', $page->page)->with('status', "Content Modified");
    }
}

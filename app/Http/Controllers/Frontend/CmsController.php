<?php

namespace App\Http\Controllers\Frontend;

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
        
        return view('frontend.cms.index', compact('page'));
    }
}

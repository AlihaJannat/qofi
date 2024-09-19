<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SwBanner;
use App\Models\SwProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = SwBanner::where('status', 1)->where('type', 'simple')->get();
        $promotionalBanners = SwBanner::where('status', 1)->where('type', 'promotional')->get();

        return view('frontend.home', compact('banners', 'promotionalBanners'));
    }

    public function shopByVendor()
    {
        return view('frontend.shop-by-vendor');
    }
}

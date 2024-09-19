<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        DB::table('sw_user_wishlists')->insert([
            'user_id' => auth()->id(),
            'sw_product_id' => $request->product_id,
        ]);

        return response()->json('added');
    }
    public function remove(Request $request)
    {
        DB::table('sw_user_wishlists')->where('user_id', auth()->id())->where('sw_product_id', $request->product_id)->delete();

        return response()->json('removed');
    }
}

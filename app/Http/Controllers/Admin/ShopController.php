<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utils\ShopUtils;
use App\Models\SwFilter;
use App\Models\SwProduct;
use App\Models\SwShop;
use App\Models\SwVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    protected $shopUtils;

    public function __construct(ShopUtils $shopUtils)
    {
        $this->shopUtils = $shopUtils;
    }

    public function index()
    {
        return view('admin.shop.index');
    }

    public function new(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => [
                    'required',
                    'max:100',
                    Rule::unique('sw_shops', 'name'),
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:3072',
                'owner_id' => 'required|unique:sw_shops,owner_id',
                // 'discount' => 'required|numeric',
                // 'discount_type' => 'required|in:percent,fixed',
                'state_id' => 'required',
                'city_id' => 'required',
                'latitude' => [
                    'required',
                    'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
                ],
                'longitude' => [
                    'required',
                    'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'
                ],
                'filters' => 'required|array',
            ]);


            $shop = $this->shopUtils->createShop($request);

             // Insert selected filters into sw_shop_filters table
            foreach ($request->filters as $filterId) {
                DB::table('sw_shop_filters')->insert([
                    'sw_shop_id' => $shop->id,
                    'sw_filter_id' => $filterId,
                ]);
            }

            DB::table('sw_vendors')->where('id', $shop->owner_id)->update([
                'sw_shop_id' => $shop->id
            ]);

            return to_route('admin.shop.new')->with('status', "Shop Added");
        }

        $states = DB::table('states')->where('country_code', 'like', 'KW')->get(['id', 'name']);

        $shopOwners = SwVendor::where('role', 'owner')
            ->whereNull('sw_shop_id')
            ->get();
        $filters = SwFilter::where('status','1')->get();


        return view('admin.shop.new', compact('states', 'shopOwners','filters'));
    }

    public function edit(Request $request, $shop_id)
    {
        $shop = SwShop::with('owner:id,email', 'state:id,name', 'city:id,name')->findorfail($shop_id);

        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => [
                    'required',
                    'max:100',
                    Rule::unique('sw_shops', 'name')->ignore($shop_id),
                ],
                'owner_id' => 'required|unique:sw_shops,owner_id,' . $shop_id,
                // 'discount' => 'required|numeric',
                // 'discount_type' => 'required|in:percent,fixed',
                'state_id' => 'required',
                'city_id' => 'required',
                'latitude' => [
                    'required',
                    'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
                ],
                'longitude' => [
                    'required',
                    'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:3072',
                'filters' => 'required|array',
            ]);

            $this->shopUtils->updateShop($request, $shop);

            // Delete old filters for the shop
            DB::table('sw_shop_filters')->where('sw_shop_id', $shop_id)->delete();

            // Insert selected filters into sw_shop_filters table
            foreach ($request->filters as $filterId) {
                DB::table('sw_shop_filters')->insert([
                    'sw_shop_id' => $shop->id,
                    'sw_filter_id' => $filterId,
                ]);
            }

            return to_route('admin.shop.edit', $shop->id)->with('status', "Shop Info Updated");
        }

        $states = DB::table('states')->where('country_code', 'like', 'KW')->get(['id', 'name']);
        $cities = DB::table('cities')->where('state_id', $shop->state_id)->get();
        $filters = SwFilter::where('status','1')->get();
        $selectedFilters = DB::table('sw_shop_filters')->where('sw_shop_id', $shop->id)->pluck('sw_filter_id')->toArray();
        $shopOwners = SwVendor::where('role', 'owner')
            ->whereNull('sw_shop_id')
            ->get();

        return view('admin.shop.view', compact('shop', 'states', 'cities', 'shopOwners','filters','selectedFilters'));
    }

    public function status(Request $request)
    {
        $shop = SwShop::findorfail($request->id);
        $shop->status = $request->status;
        $shop->save();

        return response()->json('done');
    }

    public function delete(Request $request)
    {
        SwShop::destroy($request->id);

        return response()->json('done');
    }

    public function productFeatured(Request $request)
    {
        $featured = null;
        if ($request->status == 1 || $request->status == "1") {
            $featured = now();
        }

        SwProduct::where('id', $request->id)->update([
            'featured' => $featured
        ]);

        return response()->json('done');
    }
}

<?php

namespace App\Http\Utils;

use App\Models\SwShop;
use App\Models\SwVendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShopUtils
{
    public function createShop(Request $request): SwShop
    {
        $slug = $this->generateSlug($request->name);

        $shop = new SwShop;
        $filename = '/shops/' . upload_image($request->file('image'), 'images/shops/');
        $shop->slug = $slug;
        $shop->image_name = $filename;
        $shop->name = $request->name;
        $shop->owner_id = $request->owner_id;
        // $shop->discount = $request->discount;
        // $shop->discount_type = $request->discount_type;
        $shop->sw_shop_category_id = $request->sw_shop_category_id;
        $shop->state_id = $request->state_id;
        $shop->city_id = $request->city_id;
        $shop->latitude = $request->latitude;
        $shop->longitude = $request->longitude;
        $shop->save();

        return $shop;
    }

    public function updateShop(Request $request, SwShop $shop): void
    {
        if ($request->file('image')) {
            delete_image($shop->image_name, 'images');
            $filename = '/shops/' . upload_image($request->file('image'), 'images/shops/');
            $shop->image_name = $filename;
        }

        $shop->name = $request->name;
        $this->handleOwnerChangeLogic($shop, $request->owner_id);
        $shop->owner_id = $request->owner_id;
        $shop->sw_shop_category_id = $request->sw_shop_category_id;
        // $shop->discount = $request->discount;
        // $shop->discount_type = $request->discount_type;
        $shop->state_id = $request->state_id;
        $shop->city_id = $request->city_id;
        $shop->latitude = $request->latitude;
        $shop->longitude = $request->longitude;
        $shop->save();

        return;
    }

    function generateSlug($name): string
    {
        $slug = createSlug($name);
        $existing = DB::table('sw_shops')->where('slug', $slug)->first();
        if ($existing) {
            $slug = createSlug($name . time());
        }

        return $slug;
    }

    protected function handleOwnerChangeLogic(SwShop $shop, $new_owner_id)
    {
        if ($shop->owner_id != (int)$new_owner_id) {
            // get the previous owner and assign any other shop that is related to him otherwise setting null
            if ($shop->owner_id) {

                $owner = SwVendor::find($shop->owner_id);

                // checking if owner is currently acessing this shop so changing it
                if ($owner && $owner->sw_shop_id == $shop->id) {
                    $otherShop = SwShop::where('owner_id', $owner->id)->where('id', '<>', $shop->id)->first();
                    $owner->sw_shop_id = $otherShop->id ?? null;
                    $owner->save();
                }
            }

            // assigning new owner this shop
            DB::table('sw_vendors')->where('id', $new_owner_id)->update([
                'sw_shop_id' => $shop->id
            ]);
        }
    }
}

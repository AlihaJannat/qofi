<?php

namespace App\Http\Utils;

use App\Models\SwProduct;
use App\Models\SwProductHeight;
use App\Models\SwShop;
use App\Models\SwStockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProductUtils
{
    public function create(
        array $validatedProduct,
        // array $validatedCategories,
        array $validatedColors,
        // array $validatedHeight,
        array $validatedImages,
        $all_shops
    ) {
        try {

            DB::beginTransaction();
            $vendor = auth('vendor')->user();

            if ($all_shops && $vendor->isOwner()) {
                $shopIds = SwShop::where('owner_id', $vendor->id)->pluck('id')->toArray();
            } else {
                $shopIds = [$vendor->sw_shop_id];
            }
            $imageName = upload_image($validatedProduct['image_name'], 'images/products');
            
            // additional images
            $additionalImages = [];
            foreach ($validatedImages as $image) {
                $additionalImages[] = upload_image($image, 'images/products');
            }

            foreach ($shopIds as $key => $shopId) {
                $validatedProduct['image_name'] = "/products/".copy_image(public_path('images/products/' . $imageName), "images/products");

                $validatedProduct['sw_shop_id'] = $shopId;
                $validatedProduct['slug'] = $this->generateSlug($validatedProduct['name'], $validatedProduct['sw_shop_id']);

                $product = SwProduct::create($validatedProduct);

                // $product->categories()->sync($validatedCategories);
                $product->colors()->sync($validatedColors);
                // $validatedHeight['is_default'] = 1;
                // $validatedHeight['sw_product_id'] = $product->id;

                // $height = SwProductHeight::create($validatedHeight);

                $this->insertStockHistory($product->id, 'Initial Stock', $product->stock, 'in');

                // Re-upload each additional image for each product
                foreach ($additionalImages as $image) {
                    $product->addImages(copy_image(public_path('images/products/' . $image), "images/products/additional"));
                }
            }

            delete_image($imageName, "images/products/");
            foreach ($additionalImages as $key => $image) {
                delete_image($image, "images/products/");
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function update(
        array $validatedProduct, 
        // array $validatedCategories, 
        array $validatedColors, 
        SwProduct $product)
    {
        try {
            DB::beginTransaction();
            $validatedProduct['slug'] = $this->generateSlug($validatedProduct['name'], $product->sw_shop_id);
            $product->update($validatedProduct);
            // $product->categories()->sync($validatedCategories);
            $product->colors()->sync($validatedColors);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function insertStockHistory($product_id, $desc, $qty, $type)
    {
        SwStockHistory::create([
            // 'sw_product_height_id' => $height_id,
            'sw_product_id' => $product_id,
            'description' => $desc,
            'qty' => $qty,
            'type' => $type,
        ]);
    }

    private function generateSlug($name, $shop_id): string
    {
        $slug = createSlug($name);
        $existing = DB::table('sw_products')->where('slug', $slug)->where('sw_shop_id', $shop_id)->first();
        if ($existing) {
            $slug = createSlug($name . time());
        }

        return $slug;
    }
}

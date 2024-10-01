<?php

namespace App\Http\Utils;

use App\Models\SwProduct;
use App\Models\SwProductHeight;
use App\Models\SwProductTopping;
use App\Models\SwProductVariations;
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
        $all_shops,
        array $topping,
        array $relatedProductIds
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
                $validatedProduct['image_name'] = "/products/" . copy_image(public_path('images/products/' . $imageName), "images/products");

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

            // Check if new toppings are provided
            if (isset($topping['topping_name']) && isset($topping['topping_price'])) {
                foreach ($topping['topping_name'] as $index => $toppingName) {
                    $toppingPrice = $topping['topping_price'][$index]; // Get corresponding price

                    if (!empty($toppingName) && !empty($toppingPrice)) {
                        // Create new topping and associate it with the product
                        $newTopping = new SwProductTopping();
                        $newTopping->name = $toppingName;
                        $newTopping->price = $toppingPrice;
                        $newTopping->sw_product_id = $product->id;
                        $newTopping->status = 1;
                        $newTopping->save();
                    }
                }
            }

            //related products
            if (count($relatedProductIds) > 0) {
                $product->relatedProducts()->attach($relatedProductIds);
            }

            delete_image($imageName, "images/products/");
            foreach ($additionalImages as $key => $image) {
                delete_image($image, "images/products/");
            }

            DB::commit();
            return $product;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function update(
        array $validatedProduct,
        // array $validatedCategories, 
        array $validatedColors,
        SwProduct $product,
        array $relatedProductIds
    ) {
        try {
            DB::beginTransaction();
            $validatedProduct['slug'] = $this->generateSlug($validatedProduct['name'], $product->sw_shop_id);
            $product->update($validatedProduct);
            // $product->categories()->sync($validatedCategories);
            $product->colors()->sync($validatedColors);
            $product->relatedProducts()->sync($relatedProductIds);
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

    public function getProductById($product_id)
    {
        $product = SwProduct::where('id', $product_id)->first();
        $images[] = $product->image_name;
        $images = array_merge($images, $product->images->pluck('image_name')->toArray());
        $currency = app_setting('site_currency');
        $product_details = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_images' => $images,
            'product_slug' => $product->slug,
            'product_short_description' => $product->short_description,
            'product_long_description' => $product->long_description,
            'product_country_id' => $product->getCountry()->id,
            'product_country_name' => $product->getCountry()->name,
            'product_in_stock' => $product->in_stock,
            'product_stock' => $product->stock,
            'product_price' => $product->price,
            'product_formated_price' => $currency . " " . $product->price,
            'product_discount' => $product->discount,
            'product_discount_type' => $product->discount_type,
            'product_status' => $product->status,
            'product_is_featured' => $product->is_featured,
            'product_category_id' => $product->getCategory()->id,
            'product_category_name' => $product->getCategory()->name,
            'product_sub_category_id' => $product->getSubCategory()->id,
            'product_sub_category_name' => $product->getSubCategory()->name,
            'product_created_at' => formatDate($product->created_at),
            'product_variations' => $this->getProductVariations($product_id),
            'related_products' => '',
            'reviews' => '',
        ];
        return $product_details;
    }
    public function getProductVariations($product_id)
    {

        $product = SwProduct::where('id', $product_id)->first();
        if ($product) {
            if ($product->has_variation == 1 && $product->parent_variation == 0) {
                $attribute_set = SwProductVariations::where('parent_product_id', $product_id)->select('product_variation_set_id')->groupBy('product_variation_set_id')->get();
                $variations = [];

                if ($attribute_set) {
                    foreach ($attribute_set as $item) {
                        $attribues['attribute_sets'][] = [
                            'attribute_set_id' => $item->product_variation_set_id,
                            'attribute_set_name' => $item->productAttributeSet->title,
                        ];

                        $product_attribute = SwProductVariations::where('parent_product_id', $product_id)->where('product_variation_set_id', $item->product_variation_set_id)->get();

                        foreach ($product_attribute as $item2) {
                            $variations['attributes'][$item->productAttributeSet->title . $item->productAttributeSet->id][] = [
                                'product_variation_id' => $item2->id,
                                'product_id' => $item2->product_id,
                                'attribute_id' => $item2->variation_id,
                                'attribute_name' => $item2->attribute->title,
                                'attribute_is_default' => $item2->is_default,
                            ];
                        }
                    }
                }
                return array_merge($attribues, $variations);
            } else {
                return [];
            }
        } else {
            return "product not found";
        }
    }
}

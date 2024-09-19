<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariationRequest;
use App\Http\Utils\ProductUtils;
use App\Models\ProductVariations;
use App\Models\SwAttribute;
use App\Models\SwCategory;
use App\Models\SwColor;
use App\Models\SwProduct;
use App\Models\SwProductAttributeSet;
use App\Models\SwProductImage;
use App\Models\SwUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productUtils;
    public function __construct(ProductUtils $productUtils)
    {
        $this->productUtils = $productUtils;
    }
    public function index()
    {
        return view('vendor.product.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            // dd($request->all());
            $validated = $request->validate([
                'name' => 'required|max:255',
                'country_id' => 'required|exists:countries,id',
                'in_stock' => 'required|in:0,1',
                'short_description' => 'required|max:3550',
                'long_description' => 'required',
                'image_name' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
                'sw_category_id' => 'required|exists:sw_categories,id',
                'child_category_id' => 'nullable|exists:sw_categories,id',
                'price' => 'required|numeric',
                'discount' => 'nullable',
                'has_variation' => 'nullable',
                'discount_type' => 'in:fixed,percent',
                'stock' => 'required|integer',
            ]);

            
            
            $product = $this->productUtils->create(
                $validated,
                [],
                $relationValidation['image'] ?? [],
                $request->all_shops
            );

            // return to_route('vendor.product.create')->with('status', "Product Created!");
            return to_route('vendor.product.edit', [$product->id])->with('status', 'Product updated');
        }

        // $colors = SwColor::get(['id', 'name', 'hex_code']);
        $countries = DB::table('countries')->get(['id', 'name']);
        $categories = SwCategory::where('status', 1)->get();
        $units = SwUnit::where('name', 'like', 'height')->get();
        $categories = SwCategory::where('status', 1)->whereNull('parent_id')->get(['id','name']);
        return view('vendor.product.new', get_defined_vars());
    }

    public function edit(Request $request, $product_id)
    {
        $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->findorfail($product_id);
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'country_id' => 'required|exists:countries,id',
                'in_stock' => 'required|in:0,1',
                'short_description' => 'required|max:3550',
                'long_description' => 'required',
                'sw_category_id' => 'required|exists:sw_categories,id',
                'child_category_id' => 'nullable|exists:sw_categories,id',
                'price' => 'required|numeric',
                'discount' => 'nullable',
                'has_variation' => 'nullable',
                'discount_type' => 'in:fixed,percent',
                'stock' => 'required|integer',
            ]);
            // dd($validated);
            
        $validated['has_variation'] = ($request->has('has_variation') ? 1 : 0);


            $this->productUtils->update(
                $validated, 
                // $relationValidation['category_id'], 
                [], 
                $product
            );

            return to_route('vendor.product.edit', [$product->id])->with('status', 'Product updated');
        }

        $selectedColors = DB::table('sw_product_color')->where('sw_product_id', $product->id)->pluck('sw_color_id')->toArray();
        $selectedCategories = DB::table('sw_product_category')->where('sw_product_id', $product->id)->pluck('sw_category_id')->toArray();
        $colors = SwColor::get(['id', 'name', 'hex_code']);
        $countries = DB::table('countries')->get(['id', 'name']);
        $categories = SwCategory::where('status', 1)->whereNull('parent_id')->get(['id','name']);
        $childCategories = SwCategory::where('parent_id', $product->sw_category_id)->get(['id','name']);
        $product_attribute_set = SwProductAttributeSet::where('status',1)->get();

        return view('vendor.product.edit', get_defined_vars());
    }

    public function imgChange(Request $request)
    {
        if ($request->file('image')) {
            // dd($request->all());

            //function defined in helpers.php
            $fileName = upload_image($request->file('image'), '/images/products');
            if ($request->main) {
                $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->findorfail($request->product_img_id);
                delete_image($product->image_name, '/images');
                $product->image_name = "/products/" . $fileName;
                $product->save();
            } else {
                $product_img = SwProductImage::find($request->product_img_id);

                delete_image($product_img->image_name, '/images');

                $product_img->image_name = "/products/" . $fileName;
                $product_img->save();
            }
            return redirect()->back();
        }
        return response()->json([
            "error" => "something went wrong please try letter"
        ], 400);
    }

    public function imgNew(Request $request)
    {
        if ($request->file('image')) {
            // dd($request->all());
            $product = SwProduct::findorfail($request->product_id);
            $fileName = upload_image($request->file('image'), '/images/products');
            $product->addImages($fileName);
            return redirect()->back();
        }
        abort(403);
    }

    public function imgDelete(Request $request)
    {
        $product_img = SwProductImage::find($request->id);
        //function defined in helpers.php
        delete_image($product_img->image_name, '/images');
        $product_img->delete();
        return response()->json("done");
    }

    public function changeStatus(Request $request)
    {
        $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->findorfail($request->id);
        if (!$product) {
            return response()->json('', 400);
        }

        $product->status = $request->status;
        $product->save();

        return response()->json('done');
    }

    public function delete(Request $request)
    {
        $product = SwProduct::where('sw_shop_id', auth('vendor')->user()->sw_shop_id)->findorfail($request->id);
        $product->delete();

        return response()->json('done');
    }
    
    public function getAttributes(Request $request)
    {
        
        $set = SwProductAttributeSet::where('id',$request->id)->first();
        $attributes = $set->attributes;
        return response()->json(['attributes' => $attributes]);
    }

    public function addAttribute(StoreProductVariationRequest $request){
        $parent_product = SwProduct::find($request->parent_product_id)->toArray();
       
        $validated = $request->validate([
            'parent_product_id' => 'required',
            'variation_id' => 'required',
            'in_stock' => 'required|in:0,1',
            'product_variation_set_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image_name' => 'required|mimes:jpeg,png,jpg,svg,webp|max:3072',
            'is_default' => 'nullable',
            'discount' => 'nullable',
            'discount_type' => 'in:fixed,percent',
        ]);

        $validated['is_default'] = ($request->has('is_default') ? 1 : 0);

        $this->productUtils->createAttribute(
            $validated,
            $parent_product,
            [],
            $relationValidation['image'] ?? [],
            $request->all_shops
        );
        
        return response()->json(['success' => true],200);
    }

    public function getAttribute(SwProduct $product){

    }

    
    public function deleteAttribute(Request $request)
    {
        $variation = ProductVariations::findorFail($request->id);
        // $product = SwProduct::findorfail($variation->product_id);

        $variation->product->delete();
        $variation->delete();

        return response()->json('done');
    }

    
    public function updateImage(Request $request)
    {
        
        if ($request->file('image')) {
            // dd($request->all());

            //function defined in helpers.php
            $fileName = upload_image($request->file('image'), '/images/products');
            
            $product = SwProduct::findorfail($request->product_id);
            delete_image($product->image_name, '/images');
            $product->image_name = "/products/" . $fileName;
            $product->save();
            return response()->json(['success' => true],200);
        }else{
            return response()->json([
                "error" => "something went wrong please try letter"
            ], 400);
        }
    }

    public function updateDefault(ProductVariations $product_variation){
        $this->productUtils->updateProductDefaultVariation($product_variation);
        return response()->json(['success' => true],200);
    }


}

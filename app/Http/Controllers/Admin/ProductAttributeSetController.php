<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeSet;
use App\Models\SwAttribute;
use App\Models\SwProductAttributeSet;

class ProductAttributeSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.productattributeset.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
        ]);
        $validated['slug'] = createSlug($request->title);
        $validated['status'] = 1;

        SwProductAttributeSet::create($validated);

        return response()->json(['success' => true],200);
    }

    
    public function update(Request $request)
    {   
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|max:255',
        ]);

        SwProductAttributeSet::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true],200);
    }

    public function delete(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->productAttributeSetIds)) {
                SwProductAttributeSet::whereIn('id', $request->productAttributeSetIds)->delete();
            }

            return response('done');
        }
        SwProductAttributeSet::destroy($request->id);
    }

    public function showAttributes(SwProductAttributeSet $productattributeset){
        
        return view('admin.productattributeset.attributes',['product_attribute_set_id' => $productattributeset->id]);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function addAttribute(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);
        $validated['slug'] = createSlug($request->title);
        $validated['image'] = "/attributes/" . upload_image($request->file('image'), 'images/attributes');
        $validated['status'] = 1;
        $validated['sw_product_attribute_set_id'] = $request->sw_product_attribute_set_id;

        SwAttribute::create($validated);

        return response()->json(['success' => true],200);
    }

    
    public function updateAttribute(Request $request)
    {   
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|max:255',
        ]);

        if($request->file('image'))        
            $validated['image'] = "/attributes/" . upload_image($request->file('image'), 'images/attributes');
        
        SwAttribute::where('id', $validated['id'])->update($validated);

        return response()->json(['success' => true],200);
    }
    
    public function updateAttributeImage(Request $request)
    {   
        
        if($request->file('image'))        
            $image = "/attributes/" . upload_image($request->file('image'), 'images/attributes');
        
        SwAttribute::where('id', $request->id)->update(['image' => $image]);

        return response()->json(['success' => true],200);
    }

    public function deleteAttribute(Request $request)
    {
        if ($request->action == 'bulk') {
            if (count($request->attributeIds)) {
                SwAttribute::whereIn('id', $request->attributeIds)->delete();
            }

            return response('done');
        }
        SwAttribute::destroy($request->id);
    }
}

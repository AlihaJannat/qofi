<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwProductVariations;
use App\Models\SwCoupon;
use App\Models\SwOrder;
use App\Models\SwProduct;
use App\Models\SwRole;
use App\Models\SwStockHistory;
use App\Models\SwVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class YajraController extends Controller
{
    public function shopUserData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $users = SwVendor::where('role', 'user')->where('sw_shop_id', $request->shop_id)->with('roleRel');
        if ($request->status == 'active') {
            $users = $users->where('status', 1);
        } elseif ($request->status == 'block') {
            $users = $users->where('status', 0);
        }
        return DataTables::of($users)
            ->rawColumns(['user', 'action', 'status'])
            ->addColumn('user', function ($user) {
                $name = $user->name;
                $image = asset('images' . $user->image);
                $defaultImg = asset('images/user/default.jpg');
                $html = '<div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper"><div class="avatar avatar-sm me-3">
                                    <img src="' . $image . '" alt="Avatar" class="rounded-circle"
                                    onerror="this.onerror=null; this.src=`' . $defaultImg . '`;"
                                    >
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="#" class="text-body text-truncate">
                                    <span class="fw-semibold">' . $name . '</span>
                                </a>
                            </div>';
                return $html;
            })
            ->addColumn('status', function ($user) {
                if ($user->status) {
                    return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $user->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $user->id )'>Block</button>";
                }
            })
            ->addColumn('action', function ($user) {
                $editLink = "<a href='javascript:;' data-firstname='$user->first_name' data-lastname='$user->last_name' data-email='$user->email' data-phone='$user->phone_no' data-role='$user->sw_role_id' onclick='editUser(this, $user->id )' class='dropdown-item'>Edit</a>";
                $deleteLink = "<a href='javascript:;' onclick='deleteUser(this,  $user->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
                $html = "<div class='d-inline-block'>
                    <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </a>
                    <div class='dropdown-menu dropdown-menu-end m-0'>
                        $editLink
                        <div class='dropdown-divider'></div>
                        $deleteLink
                    </div>
                </div>";
                return $html;
            })
            ->make(true);
    }

    public function roleData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }

        $roles = SwRole::where('module', 'like', 'vendor')
            ->where('sw_shop_id', $request->shop_id);
        return DataTables::of($roles)
            ->rawColumns(['action'])
            ->addColumn('action', function ($role) {
                $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                        <a href='javascript:;' onclick='getDetails($role->id)' class='dropdown-item'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='javascript:;' onclick='deleteRole(this,  $role->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                return $html;
            })
            ->make(true);
    }

    public function productData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $products = SwProduct::where('sw_shop_id', $request->shop_id)
            ->where('parent_variation', 0)
            // ->leftJoin(DB::raw('(SELECT sw_product_id, COUNT(*) as heights_count FROM sw_product_heights GROUP BY sw_product_id) as heights'), 'sw_products.id', '=', 'heights.sw_product_id')
            ->join('countries', 'sw_products.country_id', 'countries.id')->select(
                [
                    'sw_products.*',
                    'countries.name as countries.name',
                ]
            );
        if ($request->status == 'active') {
            $products = $products->where('parent_variation', 0)->where('sw_products.status', 1);
        } elseif ($request->status == 'inActive') {
            $products = $products->where('parent_variation', 0)->where('sw_products.status', 0);
        }

        return DataTables::of($products)
            ->rawColumns(
                [
                    'product',
                    'action',
                    'status',
                    // 'height'
                ]
            )
            ->addColumn('product', function ($product) use ($canEdit) {
                $route = $canEdit ? route('vendor.product.edit', $product->id) : '#';
                $name = $product->name;
                $image = asset('images' . $product->image_name);
                $defaultImg = asset('images/product/default.jpg');
                $html = '<div class="d-flex justify-content-start align-items-center product-name">
                                <div class="avatar-wrapper"><div class="avatar avatar-sm me-3">
                                    <img src="' . $image . '" alt="Avatar" class="rounded-circle"
                                    onerror="this.onerror=null; this.src=`' . $defaultImg . '`;"
                                    >
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="' . $route . '" class="text-body text-truncate">
                                    <span class="fw-semibold">' . $name . '</span>
                                </a>
                            </div>';
                return $html;
            })
            // ->addColumn('height', function ($product) use ($productHeight) {
            //     $route = $productHeight ? route('vendor.product.height.index', $product->id) : '#';
            //     $heightCount = $product->heights_count ?: 0;
            //     return "<a class='btn btn-secondary' href=$route >$heightCount</a>";
            // })
            ->addColumn('status', function ($product) {
                if ($product->status) {
                    return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $product->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $product->id )'>In-Active</button>";
                }
            })
            ->addColumn('action', function ($product) use ($canEdit, $canDelete) {
                $route = route('vendor.product.edit', $product->id);
                $editLink = $canEdit ? "<a href=$route class='dropdown-item'>Edit</a>" : '';
                $deleteLink = $canDelete ? "<a href='javascript:;' onclick='deleteProduct(this,  $product->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : '';

                $html = "<div class='d-inline-block'>
                            <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                <i class='bx bx-dots-vertical-rounded'></i>
                            </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                        $editLink
                        <div class='dropdown-divider'></div>
                        $deleteLink
                    </div>
                </div>";
                return $html;
            })
            ->make(true);
    }

    public function attributeData(Request $request)
    {

        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        // $canEdit = $request->canEdit;
        // $canDelete = $request->canDelete;
        $parentProduct = SwProduct::find($request->parentProduct);
        $variationProduct = $parentProduct->getVariationIds();
        $variations = SwProductVariations::whereIn('product_id', $variationProduct)->orderBy('created_at', 'asc')->with('product')->get();

        // dd($variations);
        // if ($request->status == 'active') {
        //     $products = $products->where('sw_products.status', 1);
        // } elseif ($request->status == 'inActive') {
        //     $products = $products->where('sw_products.status', 0);
        // }

        return DataTables::of($variations)
            ->rawColumns(
                [
                    'image',
                    'edit',
                    'delete',
                    // 'height'
                ]
            )
            ->addColumn('image', function ($variation) {
                // $route = route('vendor.product.attribte.edit', $variation->id);
                $image = asset('images' . $variation->product->image_name);
                $defaultImg = asset('images/product/default.jpg');
                $html = '<div class="d-flex">';
                $html .= "<input type='radio'  name='is_default' id='is_default' onclick='updateDefault($variation, `$variation->id`)'  " . ($variation->is_default ? 'checked' : '') . ">";
                $html .= '<div class="d-flex justify-content-start align-items-center product-name mx-4">
                                <div class="avatar-wrapper"><div class="avatar avatar-sm me-3">
                                    <img src="' . $image . '" alt="Avatar" class=""
                                    onerror="this.onerror=null; this.src=`' . $defaultImg . '`;"
                                    >
                                </div>
                            </div>';
                $html .= $variation->attribute->title;
                $html .= '</div>';
                return $html;
            })
            ->addColumn('edit', function ($variation) {

                return "<button class='btn btn-info' onclick='editAttribute($variation, `$variation->id`)' >Edit</button>";
            })
            ->addColumn('delete', function ($variation) {
                return "<button class='btn btn-danger' onclick='deleteAttribute($variation, `$variation->id`)'>Delete</button>";
            })
            ->make(true);
    }

    public function stockData(Request $request, $height_id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }

        $stocks = SwStockHistory::where('sw_product_height_id', $height_id);
        return DataTables::of($stocks)
            ->rawColumns(['action'])
            ->addColumn('action', function ($stock) {
                $html = "<a href='javascript:;' onclick='deleteStock(this,  $stock->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
                return $html;
            })
            ->make(true);
    }

    public function couponData(Request $request, $shop_id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $coupons = SwCoupon::where('sw_shop_id', $shop_id);
        return DataTables::of($coupons)
            ->rawColumns(['status', 'action'])
            ->addColumn('status', function ($coupon) {
                if ($coupon->status) {
                    return "<button class='btn btn-success active'
                                            onclick='statusChange(this,  $coupon->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            onclick='statusChange(this,  $coupon->id )'>Block</button>";
                }
            })
            ->addColumn('action', function ($coupon) use ($canEdit, $canDelete) {
                if ($coupon->sw_shop_id) {
                    $editLink = '';
                    $deleteLink = '';
                    if ($canEdit) {
                        $editLink = "<a href='javascript:;' data-code='$coupon->code' data-type='$coupon->type' data-value='$coupon->value' data-max_limit='$coupon->max_limit' data-expired_at='$coupon->expired_at' onclick='editCoupon(this, $coupon->id )' class='dropdown-item'>Edit</a>";
                    }
                    if ($canDelete) {
                        $deleteLink = "<a href='javascript:;' onclick='deleteCoupon(this,  $coupon->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
                    }
                    $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            $editLink
                            <div class='dropdown-divider'></div>
                            $deleteLink
                        </div>
                    </div>";
                    return $html;
                }
                return '';
            })
            ->make(true);
    }

    public function orderData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }

        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $vendor = auth('vendor')->user();
        $orders = SwOrder::vendorOrders($vendor->id);



        return DataTables::of($orders)
            ->rawColumns(['customer name', 'action'])
            ->addColumn('customer name', function ($order) {
                $route = '#';
                $customername = $order->user->first_name . " " . $order->user->last_name;
                $html = '<a href="' . $route . '" class="text-body text-truncate">
                                    <span class="fw-semibold">' . $customername . '</span>
                                </a>';
                return $html;
            })
            ->addColumn('customer email', function ($order) {
                return $order->user->email;
            })
            ->addColumn('payment status', function ($order) {
                return $order->payment->payment_status;
            })
            ->addColumn('action', function ($order) use ($canEdit, $canDelete) {
                $route = route('vendor.order.edit', ['order' => $order->id]);
                $editLink = $canEdit ? "<a href='$route' class='dropdown-item'>View</a>" : "";
                // $deleteLink = $canDelete ? "<a href='javascript:;' onclick='deleteUser(this,  $order->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : "";
                $html = "<div class='d-inline-block'>
                    <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </a>
                    <div class='dropdown-menu dropdown-menu-end m-0'>
                        $editLink
                    </div>
                </div>";
                return $html;
            })
            ->make(true);
    }
}

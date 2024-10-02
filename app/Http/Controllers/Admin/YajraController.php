<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwAttribute;
use App\Models\SwBanner;
use App\Models\SwCategory;
use App\Models\SwColor;
use App\Models\SwCoupon;
use App\Models\SwFilter;
use App\Models\SwMainBanner;
use App\Models\SwOrder;
use App\Models\SwProduct;
use App\Models\SwProductAttributeSet;
use App\Models\SwRole;
use App\Models\SwShop;
use App\Models\SwShopCategory;
use App\Models\SwSubscription;
use App\Models\SwUnit;
use App\Models\SwUserSubscription;
use App\Models\SwVendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class YajraController extends Controller
{
    public function userData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canDelete = $request->canDelete;
        $users = User::where('role', 'customer');
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
            ->addColumn('action', function ($user) use ($canDelete) {
                $deleteLink = $canDelete ? "<a href='javascript:;' onclick='deleteUser(this,  $user->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : '';
                $html = "<div class='d-inline-block'>
                    <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </a>
                    <div class='dropdown-menu dropdown-menu-end m-0'>
                        <div class='dropdown-divider'></div>
                        $deleteLink
                    </div>
                </div>";
                return $html;
            })
            ->make(true);
    }

    public function adminData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $users = User::where('role', 'admin')->where('id', '>', 1);
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
                $html = "<div class='d-inline-block'>
                    <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </a>
                    <div class='dropdown-menu dropdown-menu-end m-0'>
                    <a href='javascript:;' data-firstname='$user->first_name' data-lastname='$user->last_name' data-email='$user->email' data-role='$user->sw_role_id' onclick='editUser(this, $user->id )' class='dropdown-item'>Edit</a>
                        <div class='dropdown-divider'></div>
                        <a href='javascript:;' onclick='deleteUser(this,  $user->id )' class='dropdown-item text-danger delete-record'>Delete</a>
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

        $roles = SwRole::where('module', 'like', $request->module);
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

    public function vendorData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $users = SwVendor::where('role', 'owner');
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
            ->addColumn('action', function ($user) use ($canDelete, $canEdit) {
                $editLink = $canEdit ? "<a href='javascript:;' data-firstname='$user->first_name' data-lastname='$user->last_name' data-dob='$user->dob' data-email='$user->email' data-phone='$user->phone_no' data-role='$user->sw_role_id' onclick='editUser(this, $user->id )' class='dropdown-item'>Edit</a>" : "";
                $deleteLink = $canDelete ? "<a href='javascript:;' onclick='deleteUser(this,  $user->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : "";
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

    public function bannerData(Request $request)
    {
        if ($request->ajax()) {
            $banners = SwBanner::query();
            return DataTables::of($banners)
                ->rawColumns(['status', 'action', 'banner'])
                ->addColumn('banner', function ($banner) {
                    $route = route('admin.banner.edit', [$banner->id]);
                    $name = $banner->banner_text;
                    $image = filter_var($banner->image_name, FILTER_VALIDATE_URL) ? $banner->image_name : asset('images/' . $banner->image_name);
                    $defaultImg = asset('images/user/default.jpg');
                    $html = '<div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper"><div class="avatar avatar-xl me-3">
                                <img src="' . $image . '" alt="Avatar" class=""
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
                ->addColumn('status', function ($banner) {
                    if ($banner->status) {
                        return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $banner->id )'>Active</button>";
                    } else {
                        return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $banner->id )'>Block</button>";
                    }
                })
                ->addColumn('action', function ($banner) {
                    $deleteHtml = $banner->type == 'simple' ? "<a href='javascript:;' onclick='deleteBanner(this,  $banner->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : '';
                    $route = route('admin.banner.edit', [$banner->id]);
                    $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='$route' class='dropdown-item'>View</a>
                            <div class='dropdown-divider'></div>
                            $deleteHtml
                        </div>
                    </div>";
                    return $html;
                })
                ->make(true);
        }

        return response()->json([
            'message' => 'Bad Request, Something wrong with url'
        ], 400);
    }

    public function mainBannerData(Request $request)
    {
        if ($request->ajax()) {
            $banners = SwMainBanner::query();
            return DataTables::of($banners)
                ->rawColumns(['status', 'action', 'banner'])
                ->addColumn('banner', function ($banner) {
                    $route = route('admin.main-banner.edit', [$banner->id]);
                    $name = $banner->banner_text;
                    $image = filter_var($banner->image, FILTER_VALIDATE_URL) ? $banner->image : asset('images/' . $banner->image);
                    $defaultImg = asset('images/user/default.jpg');
                    $html = '<div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper"><div class="avatar avatar-xl me-3">
                                <img src="' . $image . '" alt="Avatar" class=""
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
                ->addColumn('status', function ($banner) {
                    if ($banner->status) {
                        return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $banner->id )'>Active</button>";
                    } else {
                        return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $banner->id )'>Block</button>";
                    }
                })
                ->addColumn('action', function ($banner) {
                    $deleteHtml = "<a href='javascript:;' onclick='deleteBanner(this,  $banner->id )' class='dropdown-item text-danger delete-record'>Delete</a>" ;
                    $route = route('admin.main-banner.edit', [$banner->id]);
                    $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='$route' class='dropdown-item'>View</a>
                            <div class='dropdown-divider'></div>
                            $deleteHtml
                        </div>
                    </div>";
                    return $html;
                })
                ->make(true);
        }

        return response()->json([
            'message' => 'Bad Request, Something wrong with url'
        ], 400);
    }

    public function filterData(Request $request)
    {
        if ($request->ajax()) {
            $filters = SwFilter::query();
            return DataTables::of($filters)
                ->rawColumns(['status', 'action', 'image'])
                ->addColumn('image', function ($filter) {
                    $route = route('admin.filter.edit', [$filter->id]);
                    $name = $filter->name;
                    $image = filter_var($filter->image, FILTER_VALIDATE_URL) ? $filter->image : asset('images/' . $filter->image);
                    $defaultImg = asset('images/user/default.jpg');
                    $html = '<div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper"><div class="avatar avatar-xl me-3">
                                <img src="' . $image . '" alt="Avatar" class=""
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
                ->addColumn('status', function ($filter) {
                    if ($filter->status) {
                        return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $filter->id )'>Active</button>";
                    } else {
                        return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $filter->id )'>Block</button>";
                    }
                })
                ->addColumn('action', function ($filter) {
                    $route = route('admin.filter.edit', [$filter->id]);
                    $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='$route' class='dropdown-item'>View</a>
                            <div class='dropdown-divider'></div>
                           <a href='javascript:;' onclick='deleteFilter(this,  $filter->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                    return $html;
                })
                ->make(true);
        }

        return response()->json([
            'message' => 'Bad Request, Something wrong with url'
        ], 400);
    }

    public function shopCategoryData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $categories = SwShopCategory::query();
        return DataTables::of($categories)
            ->rawColumns(['status', 'action'])
            ->addColumn('status', function ($category) {
                if ($category->status) {
                    return "<button class='btn btn-success active'
                                            onclick='statusChange(this,  $category->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            onclick='statusChange(this,  $category->id )'>Block</button>";
                }
            })
            ->addColumn('action', function ($category) use ($canEdit, $canDelete) {
                $route = route('admin.shop.category.edit', [$category->id]);
                $editLink = '';
                $deleteLink = '';
                if ($canEdit) {
                    $editLink = "<a href='$route' class='dropdown-item'>View</a>";
                }
                if ($canDelete) {
                    $deleteLink = "<a href='javascript:;' onclick='deleteCategory(this,  $category->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
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
            })
            ->make(true);
    }

    public function categoryData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $categories = SwCategory::with('parent');
        return DataTables::of($categories)
            ->rawColumns(['status', 'action'])
            ->addColumn('parent', function (SwCategory $category) {
                return $category->parent ? $category->parent->name : null;
            })
            ->addColumn('status', function ($category) {
                if ($category->status) {
                    return "<button class='btn btn-success active'
                                            onclick='statusChange(this,  $category->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            onclick='statusChange(this,  $category->id )'>Block</button>";
                }
            })
            ->addColumn('action', function ($category) use ($canEdit, $canDelete) {
                $route = route('admin.category.edit', [$category->id]);
                $editLink = '';
                $deleteLink = '';
                if ($canEdit) {
                    $editLink = "<a href='$route' class='dropdown-item'>View</a>";
                }
                if ($canDelete) {
                    $deleteLink = "<a href='javascript:;' onclick='deleteCategory(this,  $category->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
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
            })
            ->make(true);
    }

    public function shopData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $shops = SwShop::leftJoin('states', 'states.id', 'sw_shops.state_id')
            ->leftJoin('cities', 'cities.id', 'sw_shops.city_id')
            ->leftJoin('sw_vendors', 'sw_vendors.id', 'sw_shops.owner_id')
            ->select([
                'sw_shops.*',
                DB::raw('COALESCE(sw_vendors.email, "N/A") as owner_email'),
                'states.name as states.name',
                'cities.name as cities.name',
            ]);
        if ($request->status == 'active') {
            $shops = $shops->where('status', 1);
        } elseif ($request->status == 'block') {
            $shops = $shops->where('status', 0);
        }
        return DataTables::of($shops)
            ->rawColumns(['shop', 'action', 'status'])
            ->addColumn('shop', function ($shop) use ($canEdit) {
                $route = $canEdit ? route('admin.shop.edit', ['shop_id' => $shop->id]) : '#';
                $name = $shop->name;

                $image = filter_var($shop->image_name, FILTER_VALIDATE_URL) ? $shop->image_name : asset('images' . $shop->image_name);
                $defaultImg = asset('images/user/default.jpg');
                $html = '<div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper"><div class="avatar avatar-sm me-3">
                                    <img src="' . $image . '" alt="Avatar" class="rounded-circle"
                                    onerror="this.onerror=null; this.src=`' . $defaultImg . '`;"
                                    >
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <a href=' . $route . ' class="text-body text-truncate">
                                    <span class="fw-semibold">' . $name . '</span>
                                </a>
                            </div>';
                return $html;
            })
            ->addColumn('status', function ($shop) {
                if ($shop->status) {
                    return "<button class='btn btn-success active'
                                                onclick='statusChange(this,  $shop->id )'>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                                onclick='statusChange(this,  $shop->id )'>Block</button>";
                }
            })
            ->addColumn('action', function ($shop) use ($canEdit, $canDelete) {
                $route = route('admin.shop.edit', ['shop_id' => $shop->id]);
                $editLink = $canEdit ? "<a href='$route' class='dropdown-item'>Edit</a>" : "";
                $deleteLink = $canDelete ? "<a href='javascript:;' onclick='deleteUser(this,  $shop->id )' class='dropdown-item text-danger delete-record'>Delete</a>" : "";
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

    public function colorData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $colors = SwColor::query();
        return DataTables::of($colors)
            ->rawColumns(['action', 'color', 'checkbox'])
            ->addColumn('checkbox', function ($color) {
                return "<input type='checkbox' name='color_id' class='dt-checkboxes form-check-input bulk-checkbox' value='$color->id'>";
            })
            ->addColumn('color', function ($color) {
                return "<div class='d-flex align-items-center'>
                        <span class='mx-2' style='background: $color->hex_code; width: 25px; height: 25px; border-radius: 50%; display: inline-block;'></span> <span>$color->hex_code</span>
                        </div>";
            })
            ->addColumn('action', function ($color) {
                $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='javascript:;' onclick='editColor($color->id, `$color->name`, `$color->hex_code`)' class='dropdown-item'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='javascript:;' onclick='deleteColor(this,  $color->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                return $html;
            })
            ->make(true);
    }

    public function heightData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $heights = SwUnit::where('name', 'like', 'height');
        return DataTables::of($heights)
            ->rawColumns(['action', 'color', 'checkbox'])
            ->addColumn('checkbox', function ($height) {
                return "<input type='checkbox' name='height_id' class='dt-checkboxes form-check-input bulk-checkbox' value='$height->id'>";
            })
            ->addColumn('action', function ($height) {
                $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='javascript:;' onclick='editHeight($height->id, `$height->unit`)' class='dropdown-item'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='javascript:;' onclick='deleteHeight(this,  $height->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                return $html;
            })
            ->make(true);
    }

    // Product Attribute Set Table
    public function productAttributeSetData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $productAttributeSet = SwProductAttributeSet::query();
        return DataTables::of($productAttributeSet)
            ->rawColumns(['action', 'attributes', 'checkbox'])
            ->addColumn('checkbox', function ($productAttributeSet) {
                return "<input type='checkbox' name='height_id' class='dt-checkboxes form-check-input bulk-checkbox' value='$productAttributeSet->id'>";
            })
            ->addColumn('attributes', function ($productAttributeSet) {

                $route = route('admin.productattributeset.attribute.index', [$productAttributeSet->id]);
                $html = "<a href=" . $route . " class='btn btn-info active'
                                                onclick='statusFeatured(this,  $productAttributeSet->id )'>Attributes</button>";
                return $html;
            })
            ->addColumn('action', function ($productAttributeSet) {
                $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='javascript:;' onclick='editProductAttributeSet($productAttributeSet->id, `$productAttributeSet->title`)' class='dropdown-item'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='javascript:;' onclick='deleteProductAttributeSet(this,  $productAttributeSet->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                return $html;
            })
            ->make(true);
    }

    // Attribute Table
    public function attributeData(Request $request, $productAttributeSetId)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $attribute = SwAttribute::where('sw_product_attribute_set_id', $productAttributeSetId);
        return DataTables::of($attribute)
            ->rawColumns(['action', 'image', 'checkbox'])
            ->addColumn('checkbox', function ($attribute) {
                return "<input type='checkbox' name='height_id' class='dt-checkboxes form-check-input bulk-checkbox' value='$attribute->id'>";
            })
            ->addColumn('image', function ($attribute) {

                $image = asset('images/' . $attribute->image);
                $html = "<img src='$image' width=40 />";
                return $html;
            })
            ->addColumn('action', function ($attribute) {
                $html = "<div class='d-inline-block'>
                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>
                            <a href='javascript:;' onclick='editAttribute($attribute, `$attribute->id`)' class='dropdown-item'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='javascript:;' onclick='deleteAttribute(this,  $attribute->id )' class='dropdown-item text-danger delete-record'>Delete</a>
                        </div>
                    </div>";
                return $html;
            })
            ->make(true);
    }

    public function shopProduct(Request $request, $shop_id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }



        $products = SwProduct::with('defaultHeight.unit')->where('sw_shop_id', $shop_id)->where('status', 1);

        return DataTables::of($products)
            ->rawColumns(['product', 'featured'])
            ->addColumn('product', function ($product) {
                $route = '#';
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
            ->addColumn('featured', function ($product) {
                if ($product->featured) {
                    return "<button class='btn btn-success active'
                                                onclick='statusFeatured(this,  $product->id )'>Featured</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                                onclick='statusFeatured(this,  $product->id )'>Not Featured</button>";
                }
            })
            ->make(true);
    }

    public function couponData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $coupons = SwCoupon::with('shop');
        return DataTables::of($coupons)
            ->rawColumns(['status', 'action'])
            ->addColumn('shop', function ($coupon) {
                return $coupon->shop ? $coupon->shop->name : null;
            })
            ->addColumn('status', function ($coupon) {
                $click = $coupon->sw_shop_id ? '' : "onclick='statusChange(this,  $coupon->id )'";
                if ($coupon->status) {
                    return "<button class='btn btn-success active'
                                            $click>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            $click>Block</button>";
                }
            })
            ->addColumn('action', function ($coupon) use ($canEdit, $canDelete) {
                if (!$coupon->sw_shop_id) {
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

    public function subscriptionData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }
        $canEdit = $request->canEdit;
        $canDelete = $request->canDelete;
        $subscriptions = SwSubscription::with('shops');
        // dd($subscriptions->get()[0]->shops['pivot_order_count']);


        return DataTables::of($subscriptions)
            ->rawColumns(['status', 'action', 'shop', 'duration'])
            ->addColumn('duration', function ($subscription) {
                return convertDaysToMonths($subscription->duration);
            })
            ->addColumn('shop', function ($subscription) {
                $shops = $subscription->shops->map(function ($shop) {
                    return [
                        'name' => $shop->name,
                        'order_count' => $shop->pivot->order_count, // Accessing 'order_count' from the pivot table
                    ];
                });
                $duration = json_encode(convertDaysToMonths($subscription->duration));
                $count = count($subscription->subscriber);
                $route = url('admin/plan/subscriber?sub=' . $subscription->name);
                $html = "<button class='btn btn-primary' onclick='showShops($shops,$duration)' >View Shops</button><br>";
                $html .= ($count > 0) ? "<a href='$route' class='btn btn-info mt-1'  >Subscriber $count </a>" : "";
                return $html;
            })
            ->addColumn('status', function ($subscription) {
                $click = "onclick='statusChange(this,  $subscription->id )'";
                if ($subscription->status) {
                    return "<button class='btn btn-success active'
                                            $click>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            $click>Block</button>";
                }
            })
            ->addColumn('action', function ($subscription) use ($canEdit, $canDelete) {
                if (!$subscription->sw_shop_id) {
                    $editLink = '';
                    $deleteLink = '';
                    if ($canEdit) {
                        $editLink = "<a href='javascript:;'onclick='editSubscription($subscription, $subscription->id )' class='dropdown-item'>Edit</a>";
                    }
                    if ($canDelete) {
                        $deleteLink = "<a href='javascript:;' onclick='deleteSubscription(this,  $subscription->id )' class='dropdown-item text-danger delete-record'>Delete</a>";
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

    public function subscriberData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Bad Request, Something wrong with url'
            ], 400);
        }

        $subscriptions = SwUserSubscription::with('subscription');

        if ($request->has('sub') != 'null') {
            $subscriptions->whereHas('subscription', function ($query) use ($request) {
                $query->where('name', $request->input('sub'));
            });
        }

        return DataTables::of($subscriptions)
            ->rawColumns(['subscription', 'status', 'action', 'amount'])
            ->addColumn('amount', function ($subscription) {
                return $subscription->payment->amount;
            })
            ->addColumn('subscription', function ($subscription) {
                return $subscription->subscription->name;
            })
            ->addColumn('user', function ($subscription) {
                return $subscription->user->name;
            })
            ->addColumn('status', function ($subscription) {
                $click = "onclick='statusChange(this,  $subscription->id )'";
                if ($subscription->status) {
                    return "<button class='btn btn-success active'
                                            $click>Active</button>";
                } else {
                    return "<button class='btn btn-warning block'
                                            $click>Block</button>";
                }
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

        $orders = SwOrder::where('status', 1);

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
                $route = route('admin.order.edit', ['order' => $order->id]);
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use Illuminate\Http\Request;

Route::match(['get', 'post'], 'login', [Admin\AuthController::class, 'login'])->name('login')->withoutMiddleware('auth_guard:admin');

Route::get('/', function () {
    return redirect()->route('admin.dash');
});
Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dash');
Route::get('logout', [Admin\AuthController::class, 'logout'])->name('logout');

Route::get('forbidden-route', function (Request $request) {
    $prevUrl = $request->prevUrl;
    $prevUrl = urldecode($prevUrl);
    return view('admin.forbidden', compact('prevUrl'));
})->name('admin.forbidden');

// setting routes
Route::match(['get', 'post'], 'app-setting', [Admin\AppSettingController::class, 'getSettings'])->name('app.setting')->middleware('permission:owner.admin');
Route::post('add-img', [Admin\AppSettingController::class, 'addImg'])->name('add.img')->middleware('permission:owner.admin');
Route::delete('delete-img', [Admin\AppSettingController::class, 'deleteImg'])->name('delete.img')->middleware('permission:owner.admin');

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('', [Admin\UserController::class, 'index'])->name('index')->middleware('permission:customer_view');
    Route::get('yajra-data', [Admin\YajraController::class, 'userData'])->name('yajra');
    Route::get('status', [Admin\UserController::class, 'changeStatus'])->name('status')->middleware('permission:customer_edit');
    // Route::get('pending', [Admin\UserController::class, 'pending'])->name('pending')->middleware('permission:customer_pending');
    // Route::get('approve', [Admin\UserController::class, 'approve'])->name('approve')->middleware('permission:customer_pending');
    // Route::get('yajra-data-pending', [Admin\YajraController::class, 'pendingUserData'])->name('yajra.pending');
    Route::get('delete', [Admin\UserController::class, 'delete'])->name('delete')->middleware('permission:customer_delete');
});

Route::prefix('admin')->as('admin.')->middleware('permission:owner.admin')->group(function () {
    Route::get('', [Admin\AdminController::class, 'index'])->name('index');
    Route::post('add', [Admin\AdminController::class, 'add'])->name('add');
    Route::post('update', [Admin\AdminController::class, 'update'])->name('update');
    Route::get('yajra', [Admin\YajraController::class, 'adminData'])->name('yajra');
    Route::get('delete', [Admin\AdminController::class, 'delete'])->name('delete');
    Route::get('status', [Admin\AdminController::class, 'changeStatus'])->name('status');
});

Route::prefix('role')->as('role.')->middleware('permission:owner.admin')->group(function () {
    Route::get('', [Admin\RoleController::class, 'index'])->name('index');
    Route::get('delete', [Admin\RoleController::class, 'delete'])->name('delete');
    Route::get('get-details', [Admin\RoleController::class, 'getDetails'])->name('getDetails');
    Route::post('add', [Admin\RoleController::class, 'add'])->name('add');
    Route::post('edit', [Admin\RoleController::class, 'edit'])->name('edit');
    Route::get('yajra', [Admin\YajraController::class, 'roleData'])->name('yajra');
});

Route::prefix('vendor')->as('vendor.')->middleware('permission:vendor_view')->group(function () {
    Route::get('/', [Admin\VendorController::class, 'index'])->name('index');
    Route::post('add', [Admin\VendorController::class, 'add'])->name('add');
    Route::post('update', [Admin\VendorController::class, 'update'])->name('update')->middleware('permission:vendor_edit');
    Route::get('yajra', [Admin\YajraController::class, 'vendorData'])->name('yajra');
    Route::get('delete', [Admin\VendorController::class, 'delete'])->name('delete')->middleware('permission:vendor_delete');
    Route::get('status', [Admin\VendorController::class, 'changeStatus'])->name('status')->middleware('permission:vendor_edit');
});

// banner
Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
    Route::get('/', [Admin\BannerController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'bannerData'])->name('yajra');
    Route::match(['get', 'post'], '/new', [Admin\BannerController::class, 'new'])->name('new');
    Route::match(['get', 'post'], '/edit/{id}', [Admin\BannerController::class, 'edit'])->name('edit');
    Route::delete('delete', [Admin\BannerController::class, 'delete'])->name('delete');
    Route::get('status', [Admin\BannerController::class, 'changeStatus'])->name('status');
    Route::post('/image/change', [Admin\BannerController::class, 'imageChange'])->name('img.change');
});

// main banner
Route::group(['prefix' => 'main-banner', 'as' => 'main-banner.'], function () {
    Route::get('/', [Admin\MainBannerController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'mainBannerData'])->name('yajra');
    Route::match(['get', 'post'], '/new', [Admin\MainBannerController::class, 'new'])->name('new');
    Route::match(['get', 'post'], '/edit/{id}', [Admin\MainBannerController::class, 'edit'])->name('edit');
    Route::delete('delete', [Admin\MainBannerController::class, 'delete'])->name('delete');
    Route::get('status', [Admin\MainBannerController::class, 'changeStatus'])->name('status');
    Route::post('/image/change', [Admin\MainBannerController::class, 'imageChange'])->name('img.change');
});

//filter
Route::group(['prefix' => 'filter', 'as' => 'filter.'], function () {
    Route::get('/', [Admin\FilterController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'filterData'])->name('yajra');
    Route::match(['get', 'post'], '/new', [Admin\FilterController::class, 'new'])->name('new');
    Route::match(['get', 'post'], '/edit/{id}', [Admin\FilterController::class, 'edit'])->name('edit');
    Route::delete('delete', [Admin\FilterController::class, 'delete'])->name('delete');
    Route::get('status', [Admin\FilterController::class, 'changeStatus'])->name('status');
    Route::post('/image/change', [Admin\FilterController::class, 'imageChange'])->name('img.change');
});

Route::prefix('shop')->as('shop.')->group(function () {
    Route::get('/', [Admin\ShopController::class, 'index'])->name('index')->middleware('permission:shop_view');
    Route::match(['get', 'post'], 'new', [Admin\ShopController::class, 'new'])->name('new')->middleware('permission:shop_create');
    Route::match(['get', 'post'], 'edit/{shop_id}', [Admin\ShopController::class, 'edit'])->name('edit')->middleware('permission:shop_edit');
    Route::get('yajra', [Admin\YajraController::class, 'shopData'])->name('yajra');
    Route::get('status', [Admin\ShopController::class, 'status'])->name('status')->middleware('permission:shop_edit');
    Route::get('delete', [Admin\ShopController::class, 'delete'])->name('delete')->middleware('permission:shop_delete');
    Route::get('product/{shop_id}', [Admin\YajraController::class, 'shopProduct'])->name('product');
    Route::get('product-featured', [Admin\ShopController::class, 'productFeatured'])->name('product.featured');

    Route::prefix('category')->as('category.')->group(function () {
        Route::get('/', [Admin\ShopCategoryController::class, 'index'])->name('index')->middleware('permission:shopCategory_view');
        Route::get('yajra', [Admin\YajraController::class, 'shopCategoryData'])->name('yajra');
        Route::match(['get', 'post'], '/new', [Admin\ShopCategoryController::class, 'new'])->name('new')->middleware('permission:shopCategory_create');
        Route::match(['get', 'post'], '/edit/{id}', [Admin\ShopCategoryController::class, 'edit'])->name('edit')->middleware('permission:shopCategory_edit');
        Route::delete('delete', [Admin\ShopCategoryController::class, 'delete'])->name('delete')->middleware('permission:shopCategory_delete');
        Route::get('status', [Admin\ShopCategoryController::class, 'changeStatus'])->name('status')->middleware('permission:shopCategory_edit');
        Route::post('/image/change', [Admin\ShopCategoryController::class, 'imageChange'])->name('img.change');
    });
});

Route::prefix('category')->as('category.')->group(function () {
    Route::get('/', [Admin\CategoryController::class, 'index'])->name('index')->middleware('permission:category_view');
    Route::get('yajra', [Admin\YajraController::class, 'categoryData'])->name('yajra');
    Route::match(['get', 'post'], '/new', [Admin\CategoryController::class, 'new'])->name('new')->middleware('permission:category_create');
    Route::match(['get', 'post'], '/edit/{id}', [Admin\CategoryController::class, 'edit'])->name('edit')->middleware('permission:category_edit');
    Route::delete('delete', [Admin\CategoryController::class, 'delete'])->name('delete')->middleware('permission:category_delete');
    Route::get('status', [Admin\CategoryController::class, 'changeStatus'])->name('status')->middleware('permission:category_edit');
    Route::post('/image/change', [Admin\CategoryController::class, 'imageChange'])->name('img.change');
    Route::post('/get-children', [Admin\CategoryController::class, 'getChildCategories'])->name('get.children');
});

Route::prefix('calendar')->as('calendar.')->group(function () {
    Route::get('days', [Admin\CalendarController::class, 'days'])->name('day');
    Route::get('times', [Admin\CalendarController::class, 'times'])->name('time');
    Route::get('day-status', [Admin\CalendarController::class, 'dayStatus'])->name('day.status');
    Route::get('time-status', [Admin\CalendarController::class, 'timeStatus'])->name('time.status');
});

Route::prefix('order')->as('order.')->group(function () {
    Route::get('/', [Admin\OrderController::class, 'index'])->name('index')->middleware('permission:order_view');
    Route::get('/yajra', [Admin\YajraController::class, 'orderData'])->name('yajra');
    Route::post('/add', [Admin\OrderController::class, 'add'])->name('add')->middleware('permission:order_create');
    Route::get('/{order}/edit', [Admin\OrderController::class, 'edit'])->name('edit')->middleware('permission:order_edit');
    Route::post('/update', [Admin\OrderController::class, 'update'])->name('update')->middleware('permission:order_edit');
    Route::put('/updateStatus/{order}', [Admin\OrderController::class, 'updateStatus'])->name('updateStatus')->middleware('permission:order_edit');
    Route::delete('/delete', [Admin\OrderController::class, 'delete'])->name('delete')->middleware('permission:order_delete');
});

Route::prefix('plan')->as('plan.')->group(function () {
    Route::prefix('subscription')->as('subscription.')->group(function () {
        Route::get('/', [Admin\SubscriptionController::class, 'index'])->name('index')->middleware('permission:subscription_view');
        Route::get('/yajra', [Admin\YajraController::class, 'subscriptionData'])->name('yajra');
        Route::get('status', [Admin\SubscriptionController::class, 'changeStatus'])->name('status')->middleware('permission:subscription_edit');
        Route::post('/add', [Admin\SubscriptionController::class, 'add'])->name('add')->middleware('permission:subscription_create');
        Route::post('/update', [Admin\SubscriptionController::class, 'update'])->name('update')->middleware('permission:subscription_edit');
        Route::delete('/delete', [Admin\SubscriptionController::class, 'delete'])->name('delete')->middleware('permission:subscription_delete');
    });
    Route::prefix('subscriber')->as('subscriber.')->group(function () {
        Route::get('/', [Admin\SubscriptionController::class, 'subscribersIndex'])->name('view')->middleware('permission:subscriber_view');
        Route::get('/yajra', [Admin\YajraController::class, 'subscriberData'])->name('yajra');
        Route::get('/status', [Admin\SubscriptionController::class, 'subscriberChangeStatus'])->name('status')->middleware('permission:subscriber_edit');
    });
});
Route::prefix('coupon')->as('coupon.')->group(function () {
    Route::get('/', [Admin\CouponController::class, 'index'])->name('index')->middleware('permission:coupon_view');
    Route::get('/yajra', [Admin\YajraController::class, 'couponData'])->name('yajra');
    Route::get('status', [Admin\CouponController::class, 'changeStatus'])->name('status')->middleware('permission:coupon_edit');
    Route::post('/add', [Admin\CouponController::class, 'add'])->name('add')->middleware('permission:coupon_create');
    Route::post('/update', [Admin\CouponController::class, 'update'])->name('update')->middleware('permission:coupon_edit');
    Route::delete('/delete', [Admin\CouponController::class, 'delete'])->name('delete')->middleware('permission:coupon_delete');
});

Route::prefix('color')->as('color.')->group(function () {
    Route::get('/', [Admin\ColorController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'colorData'])->name('yajra');
    Route::post('/add', [Admin\ColorController::class, 'add'])->name('add');
    Route::post('/update', [Admin\ColorController::class, 'update'])->name('update');
    Route::delete('/delete', [Admin\ColorController::class, 'delete'])->name('delete');
});

Route::prefix('height')->as('height.')->group(function () {
    Route::get('/', [Admin\HeightController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'heightData'])->name('yajra');
    Route::post('/add', [Admin\HeightController::class, 'add'])->name('add');
    Route::post('/update', [Admin\HeightController::class, 'update'])->name('update');
    Route::delete('/delete', [Admin\HeightController::class, 'delete'])->name('delete');
});

Route::prefix('productattributeset')->as('productattributeset.')->group(function () {
    Route::get('/', [Admin\ProductAttributeSetController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'productAttributeSetData'])->name('yajra');
    Route::post('/add', [Admin\ProductAttributeSetController::class, 'add'])->name('add');
    Route::post('/update', [Admin\ProductAttributeSetController::class, 'update'])->name('update');
    Route::delete('/delete', [Admin\ProductAttributeSetController::class, 'delete'])->name('delete');

    Route::get('/{productattributeset}', [Admin\ProductAttributeSetController::class, 'showAttributes'])->name('attribute.index');
    Route::get('attribute/yajra/{productattributeset}', [Admin\YajraController::class, 'attributeData'])->name('attribute.yajra');
    Route::post('/attribute/add', [Admin\ProductAttributeSetController::class, 'addAttribute'])->name('attribute.add');
    Route::post('/attribute/update', [Admin\ProductAttributeSetController::class, 'updateAttribute'])->name('attribute.update');
    Route::post('/attribute/imgupdate', [Admin\ProductAttributeSetController::class, 'updateAttributeImage'])->name('attribute.imgupdate');
    Route::delete('/attribute/delete', [Admin\ProductAttributeSetController::class, 'deleteAttribute'])->name('attribute.delete');
});

Route::prefix('productorigin')->as('productorigin.')->group(function () {
    Route::get('/', [Admin\ProductoriginController::class, 'index'])->name('index');
    Route::get('yajra', [Admin\YajraController::class, 'productOriginData'])->name('yajra');
    Route::post('/add', [Admin\ProductoriginController::class, 'add'])->name('add');
    Route::post('/update', [Admin\ProductoriginController::class, 'update'])->name('update');
    Route::delete('/delete', [Admin\ProductoriginController::class, 'delete'])->name('delete');
    Route::get('/status', [Admin\ProductoriginController::class, 'changeStatus'])->name('status');
});
Route::prefix('cms')->as('cms.')->group(function () {
    Route::get('{page_name}', [Admin\CmsController::class, 'get'])->name('get');
    Route::post('edit/{page}', [Admin\CmsController::class, 'edit'])->name('edit');
});

Route::prefix('location')->as('location.')->group(function () {
    Route::get('get-states', [Admin\LocationController::class, 'getStates'])->name('get.states');
    Route::get('get-cities', [Admin\LocationController::class, 'getCities'])->name('get.cities');
});

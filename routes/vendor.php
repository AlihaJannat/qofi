<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor;
use Illuminate\Http\Request;


Route::match(['get', 'post'], 'login', [Vendor\AuthController::class, 'login'])->name('login')->withoutMiddleware('auth_guard:vendor');

Route::get('/', function () {
    return redirect()->route('dash');
});
Route::get('dashboard', [Vendor\DashboardController::class, 'index'])->name('dash');
Route::get('logout', [Vendor\AuthController::class, 'logout'])->name('logout');
Route::get('change-shop/{shop_id}', [Vendor\AuthController::class, 'changeCurrentShop'])->name('change.shop');
Route::post('/get-category-children', [vendor\DashboardController::class, 'getChildCategories'])->name('get.children');

Route::prefix('role')->as('role.')->middleware('vendor_permission:vendor.admin')->group(function () {
    Route::get('', [Vendor\RoleController::class, 'index'])->name('index');
    Route::get('delete', [Vendor\RoleController::class, 'delete'])->name('delete');
    Route::get('get-details', [Vendor\RoleController::class, 'getDetails'])->name('getDetails');
    Route::post('add', [Vendor\RoleController::class, 'add'])->name('add');
    Route::post('edit', [Vendor\RoleController::class, 'edit'])->name('edit');
    Route::get('yajra', [Vendor\YajraController::class, 'roleData'])->name('yajra');
});

Route::prefix('user')->as('user.')->group(function () {
    Route::get('/', [Vendor\UserController::class, 'index'])->name('index')->middleware('vendor_permission:vendor.admin');
    Route::post('add', [Vendor\UserController::class, 'add'])->name('add');
    Route::post('update', [Vendor\UserController::class, 'update'])->name('update');
    Route::get('yajra', [Vendor\YajraController::class, 'shopUserData'])->name('yajra')->withoutMiddleware('auth_guard:vendor');
    Route::get('delete', [Vendor\UserController::class, 'delete'])->name('delete');
    Route::get('status', [Vendor\UserController::class, 'changeStatus'])->name('status');
});

Route::prefix('product')->as('product.')->middleware('vendor_permission:product_view')->group(function () {
    Route::get('/', [Vendor\ProductController::class, 'index'])->name('index');
    Route::match(['get', 'post'], 'create', [Vendor\ProductController::class, 'create'])->name('create')->middleware('vendor_permission:product_create');
    Route::match(['get', 'post'], 'edit/{product}', [Vendor\ProductController::class, 'edit'])->name('edit')->middleware('vendor_permission:product_edit');
    Route::get('yajra', [Vendor\YajraController::class, 'productData'])->name('yajra');
    Route::get('delete', [Vendor\ProductController::class, 'delete'])->name('delete')->middleware('vendor_permission:product_delete');
    Route::get('status', [Vendor\ProductController::class, 'changeStatus'])->name('status')->middleware('vendor_permission:product_edit');
    Route::post('image/change', [Vendor\ProductController::class, 'imgChange'])->name('img.change')->middleware('vendor_permission:product_edit');
    Route::post('image/delete', [Vendor\ProductController::class, 'imgDelete'])->name('img.delete')->middleware('vendor_permission:product_edit');
    Route::post('image/new', [Vendor\ProductController::class, 'imgNew'])->name('img.new');
    Route::post('/get-attribute', [vendor\ProductController::class, 'getAttributes'])->name('get.attribute');

    Route::prefix('attribute')->name('attribute.')->middleware('vendor_permission:product_attribute')->group(function () {
        Route::get('/yajra', [Vendor\YajraController::class, 'attributeData'])->name('yajra');
        Route::post('/add', [Vendor\ProductController::class, 'addAttribute'])->name('add');
        Route::post('/update', [Vendor\ProductController::class, 'updateAttribute'])->name('update');
        Route::post('/updateimage', [Vendor\ProductController::class, 'updateImage'])->name('imgupdate');
        Route::get('/delete', [Vendor\ProductController::class, 'deleteAttribute'])->name('delete');
        Route::get('/updatedefaultvariation/{product_variation}', [Vendor\ProductController::class, 'updateDefault'])->name('updateDefault');
    });

    Route::prefix('height')->name('height.')->middleware('vendor_permission:product_height')->group(function () {
        Route::get('/{product_id}', [Vendor\ProductHeightController::class, 'index'])->name('index');
        Route::post('/add/{product_id}', [Vendor\ProductHeightController::class, 'add'])->name('add');
        Route::post('/update/{product_id}', [Vendor\ProductHeightController::class, 'update'])->name('update');
        Route::post('/delete/{product_id}', [Vendor\ProductHeightController::class, 'delete'])->name('delete');
        Route::post('/status/{product_id}', [Vendor\ProductHeightController::class, 'status'])->name('status');

        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('{height_id}', [Vendor\StockController::class, 'index'])->name('index');
            Route::get('yajra/{height_id}', [Vendor\YajraController::class, 'stockData'])->name('yajra');
            Route::post('add/{height_id}', [Vendor\StockController::class, 'add'])->name('add');
            Route::delete('delete', [Vendor\StockController::class, 'delete'])->name('delete');
        });
    });
});

Route::prefix('calendar')->as('calendar.')->group(function () {
    Route::get('days', [Vendor\CalendarController::class, 'days'])->name('day');
    Route::post('day-add', [Vendor\CalendarController::class, 'dayAdd'])->name('day.add');
    Route::post('day-edit', [Vendor\CalendarController::class, 'dayEdit'])->name('day.edit');
    Route::delete('day-delete', [Vendor\CalendarController::class, 'dayDelete'])->name('day.delete');
    Route::get('day-status', [Vendor\CalendarController::class, 'dayStatus'])->name('day.status');
});

Route::prefix('coupon')->as('coupon.')->group(function () {
    Route::get('/', [Vendor\CouponController::class, 'index'])->name('index')->middleware('permission:mycoupon_view');
    Route::get('/yajra/{shop_id}', [Vendor\YajraController::class, 'couponData'])->name('yajra')->withoutMiddleware('auth_guard:vendor');
    Route::get('status', [Vendor\CouponController::class, 'changeStatus'])->name('status')->middleware('permission:mycoupon_edit');
    Route::post('/add', [Vendor\CouponController::class, 'add'])->name('add')->middleware('permission:mycoupon_create');
    Route::post('/update', [Vendor\CouponController::class, 'update'])->name('update')->middleware('permission:mycoupon_edit');
    Route::delete('/delete', [Vendor\CouponController::class, 'delete'])->name('delete')->middleware('permission:mycoupon_delete');
});

Route::prefix('order')->as('order.')->middleware('vendor_permission:vendor.admin')->group(function () {
    Route::get('/', [Vendor\OrderController::class, 'index'])->name('index');
    Route::get('/{order}/edit', [vendor\OrderController::class, 'edit'])->name('edit');
    Route::post('/update', [vendor\OrderController::class, 'update'])->name('update');
    Route::get('yajra', [Vendor\YajraController::class, 'orderData'])->name('yajra');
});

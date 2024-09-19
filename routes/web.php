<?php

use App\Http\Controllers\Frontend;
use App\Http\Controllers\Frontend\EmailVerificationController;
use App\Http\Controllers\Select2Controller;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;



// Route::get('/sendemail', [EmailVerificationController::class, 'sendTestEmail']);
Route::get('/', [Frontend\HomeController::class, 'index'])->name('home');

Route::get('/login', [Frontend\AuthController::class, 'login'])->name('login')->middleware('guest');
Route::get('/register', [Frontend\AuthController::class, 'register'])->name('register');
Route::get('/logout', [Frontend\AuthController::class, 'logout'])->name('logout');
Route::get('/forget-password', [Frontend\AuthController::class, 'forgetPassword'])->name('forgetPassword')->middleware('guest');
Route::match(['get', 'post'], '/reset-password', [Frontend\AuthController::class, 'resetPassword'])->name('reset.password');

//verify email
Route::get('/email/verify', function () {
    return view('frontend.auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth'])
    ->name('verification.resend');

//reset (forget) password 
// Route::get('password/reset', RequestResetEmail::class)->name('password.request');
Route::get('/forget-password', [Frontend\AuthController::class, 'forgetPassword'])->name('forget.password');
Route::get('password/reset/{token}/{email}', [Frontend\AuthController::class, 'resetPassword'])->name('password.reset');


Route::get('shop-by-vendor', [Frontend\HomeController::class, 'shopByVendor'])->name('shop.by.vendor');
Route::prefix('products')->as('product.')->group(function () {
    Route::get('/{shop_slug?}', [Frontend\ProductController::class, 'index'])->name('index');
    Route::get('/{shop:slug}/{slug}', [Frontend\ProductController::class, 'detail'])->name('detail');
});

Route::prefix('user')->as('user.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('profile', [Frontend\UserController::class, 'profile'])->name('profile');
    Route::get('password-change', [Frontend\UserController::class, 'passwordChange'])->name('password.change');
    Route::get('favourite', [Frontend\UserController::class, 'favourite'])->name('favourite');
    Route::get('profile-delete', [Frontend\UserController::class, 'profileDelete'])->name('profile.delete');
    Route::post('update-image', [Frontend\UserController::class, 'updateImage'])->name('update.image');

    //

});

Route::match(['get', 'post'], 'checkout', [Frontend\OrderController::class, 'index']);
Route::get('/subscriptions', [Frontend\SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::post('/subscriptions/subscribe/{id}', [Frontend\SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe')->middleware('auth');

Route::prefix('wishlist')->as('wishlist.')->middleware(['auth', 'verified'])->group(function () {
    Route::post('add', [Frontend\WishlistController::class, 'add'])->name('add');
    Route::post('remove', [Frontend\WishlistController::class, 'remove'])->name('remove');
});

Route::prefix('cart')->as('cart.')->group(function () {
    Route::post('add', [Frontend\CartController::class, 'add'])->name('add');
});


Route::get('cms/{page}', [Frontend\CmsController::class, 'get'])->name('cms.page');

Route::get('clear-navlink', function () {
    Cache::forget('nav_links');
});

Route::get('product-select2', [Select2Controller::class, 'products'])->name('product.select2');

Livewire::setScriptRoute(function ($handle) {
    return Route::get(env('LIVEWIRE_BASE_ROUTE', '/') . 'livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::get(env('LIVEWIRE_BASE_ROUTE', '/') . 'livewire/update', $handle);
});

<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthGuardMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\VendorPermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web','auth_guard:admin'])->prefix('admin')->as('admin.')
                ->group(base_path('routes/admin.php'));
            Route::middleware(['web','auth_guard:vendor'])->prefix('vendor')->as('vendor.')
                ->group(base_path('routes/vendor.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth_guard' => AuthGuardMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'vendor_permission' => VendorPermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

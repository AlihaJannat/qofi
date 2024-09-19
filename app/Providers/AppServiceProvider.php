<?php

namespace App\Providers;

use App\Models\SwAppSetting;
use App\Models\SwCategory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settings = Cache::remember('app_settings', 86400, function () {
            return SwAppSetting::pluck('value', 'key')->toArray();
        });

        $this->app->singleton('app_settings', function () use ($settings) {
            return $settings;
        });

        // Cache the nav links for 10 mins (600 seconds)
        $navLinks = Cache::remember('nav_links', 600, function () {
            return SwCategory::with('children:id,parent_id,name', 'navProducts')
                ->whereNull('parent_id')
                ->where('show_nav', 1)
                ->where('status', 1)
                ->get();
        });

        // Share $navLinks variable with all views
        View::share('navLinks', $navLinks);
    }
}

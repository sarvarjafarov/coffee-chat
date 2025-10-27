<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        $settings = collect();

        try {
            $settings = collect(\App\Models\SiteSetting::query()->pluck('value', 'key'));
        } catch (\Throwable $e) {
            // Table may not exist yet during initial setup.
        }

        View::share('siteSettings', $settings);
        View::share('mbaJobs', collect(config('mba_jobs.full_time')));
        View::share('mbaInternships', collect(config('mba_jobs.internships')));
    }
}

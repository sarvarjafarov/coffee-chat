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

        $envFallbacks = [
            'google_search_api_key' => env('GOOGLE_SEARCH_API_KEY'),
            'google_cse_id' => env('GOOGLE_CSE_ID'),
        ];

        foreach ($envFallbacks as $key => $value) {
            if ($value && ! $settings->has($key)) {
                $settings->put($key, $value);
            }
        }

        View::share('siteSettings', $settings);
        View::share('mbaJobs', collect(config('mba_jobs.full_time')));
        View::share('mbaInternships', collect(config('mba_jobs.internships')));
    }
}

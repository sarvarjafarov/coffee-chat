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

        $baseGoogleConfig = config('services.google', []);
        $baseLinkedinConfig = config('services.linkedin', []);
        $baseAnalyticsConfig = config('services.google_analytics', []);

        $settings = collect();

        try {
            $settings = collect(\App\Models\SiteSetting::query()->pluck('value', 'key'));
        } catch (\Throwable $e) {
            // Table may not exist yet during initial setup.
        }

        $envFallbacks = [
            'google_search_api_key' => env('GOOGLE_SEARCH_API_KEY'),
            'google_cse_id' => env('GOOGLE_CSE_ID'),
            'google_client_id' => env('GOOGLE_CLIENT_ID'),
            'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'google_redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            'google_analytics_measurement_id' => config('services.google_analytics.measurement_id'),
            'linkedin_client_id' => env('LINKEDIN_CLIENT_ID'),
            'linkedin_client_secret' => env('LINKEDIN_CLIENT_SECRET'),
            'linkedin_redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
        ];

        foreach ($envFallbacks as $key => $value) {
            if ($value && ! $settings->has($key)) {
                $settings->put($key, $value);
            }
        }

        config([
            'services.google.client_id' => $settings->get('google_client_id', $baseGoogleConfig['client_id'] ?? env('GOOGLE_CLIENT_ID')),
            'services.google.client_secret' => $settings->get('google_client_secret', $baseGoogleConfig['client_secret'] ?? env('GOOGLE_CLIENT_SECRET')),
            'services.google.redirect' => $settings->get('google_redirect_uri', $baseGoogleConfig['redirect'] ?? env('GOOGLE_REDIRECT_URI')),
            'services.google_analytics.measurement_id' => $settings->get('google_analytics_measurement_id', $baseAnalyticsConfig['measurement_id'] ?? env('GOOGLE_ANALYTICS_MEASUREMENT_ID')),
            'services.linkedin.client_id' => $settings->get('linkedin_client_id', $baseLinkedinConfig['client_id'] ?? env('LINKEDIN_CLIENT_ID')),
            'services.linkedin.client_secret' => $settings->get('linkedin_client_secret', $baseLinkedinConfig['client_secret'] ?? env('LINKEDIN_CLIENT_SECRET')),
            'services.linkedin.redirect' => $settings->get('linkedin_redirect_uri', $baseLinkedinConfig['redirect'] ?? env('LINKEDIN_REDIRECT_URI')),
        ]);

        View::share('siteSettings', $settings);
        View::share('mbaJobs', collect(config('mba_jobs.full_time')));
        View::share('mbaInternships', collect(config('mba_jobs.internships')));
    }
}

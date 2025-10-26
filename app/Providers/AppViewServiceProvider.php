<?php

namespace App\Providers;

use App\Models\WorkspaceField;
use Illuminate\Support\ServiceProvider;

class AppViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('profile.partials.update-profile-information-form', function ($view) {
            $view->with('workspaceFieldCount', WorkspaceField::count());
        });
    }
}

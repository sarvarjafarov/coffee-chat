<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user?->is_admin) {
            return redirect()->route('workspace.coffee-chats.index');
        }

        $pages = Page::with('components')->orderBy('name')->get();
        $recentPosts = Post::latest()->take(4)->get();

        $settings = [];

        if (Schema::hasTable('site_settings')) {
            try {
                $settings = SiteSetting::query()->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                report($e);
                $settings = [];
            }
        }

        $settings = array_merge([
            'google_search_api_key' => env('GOOGLE_SEARCH_API_KEY'),
            'google_cse_id' => env('GOOGLE_CSE_ID'),
            'google_client_id' => config('services.google.client_id'),
            'google_client_secret' => config('services.google.client_secret'),
            'google_redirect_uri' => config('services.google.redirect'),
            'linkedin_client_id' => config('services.linkedin.client_id'),
            'linkedin_client_secret' => config('services.linkedin.client_secret'),
            'linkedin_redirect_uri' => config('services.linkedin.redirect'),
        ], $settings);

        return view('dashboard', [
            'pages' => $pages,
            'recentPosts' => $recentPosts,
            'settings' => $settings,
            'stats' => [
                'pages' => $pages->count(),
                'components' => $pages->sum(fn (Page $page) => $page->components->count()),
                'posts' => Post::count(),
            ],
        ]);
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'accent_start' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'accent_end' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'surface' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'surface_alt' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'text_primary' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'text_muted' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
        ], [
            'accent_start.regex' => 'Please provide a valid hex colour.',
            'accent_end.regex' => 'Please provide a valid hex colour.',
            'surface.regex' => 'Please provide a valid hex colour.',
            'surface_alt.regex' => 'Please provide a valid hex colour.',
            'text_primary.regex' => 'Please provide a valid hex colour.',
            'text_muted.regex' => 'Please provide a valid hex colour.',
        ]);

        if (Schema::hasTable('site_settings')) {
            SiteSetting::updateMany($validated);
        }

        return back()->with('status', 'Theme colours updated successfully.');
    }

    public function updateSearchSettings(Request $request): RedirectResponse
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'google_search_api_key' => ['nullable', 'string', 'max:255'],
            'google_cse_id' => ['nullable', 'string', 'max:255'],
            'google_client_id' => ['nullable', 'string', 'max:255'],
            'google_client_secret' => ['nullable', 'string', 'max:255'],
            'google_redirect_uri' => ['nullable', 'url', 'max:255'],
            'linkedin_client_id' => ['nullable', 'string', 'max:255'],
            'linkedin_client_secret' => ['nullable', 'string', 'max:255'],
            'linkedin_redirect_uri' => ['nullable', 'url', 'max:255'],
        ]);

        if (Schema::hasTable('site_settings')) {
            SiteSetting::updateMany($validated);
        }

        return back()->with('status', 'Integration credentials updated successfully.');
    }
}

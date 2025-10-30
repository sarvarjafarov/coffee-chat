<?php

use App\Http\Controllers\Admin\ChannelController as AdminChannelController;
use App\Http\Controllers\Admin\CoffeeChatController as AdminCoffeeChatController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PageComponentController as AdminPageComponentController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SeoManagerController;
use App\Http\Controllers\Admin\WorkspaceFieldController as AdminWorkspaceFieldController;
use App\Http\Controllers\Admin\NetworkHealthAssessmentController as AdminNetworkHealthAssessmentController;
use App\Http\Controllers\Admin\WorkspaceMenuController as AdminWorkspaceMenuController;
use App\Http\Controllers\Admin\SiteMenuController as AdminSiteMenuController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Workspace\CoffeeChatController as WorkspaceCoffeeChatController;
use App\Http\Controllers\Workspace\AnalyticsController as WorkspaceAnalyticsController;
use App\Http\Controllers\Workspace\ProfileController as WorkspaceProfileController;
use App\Http\Controllers\Workspace\TeamFinderController as WorkspaceTeamFinderController;
use App\Http\Controllers\NetworkHealthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingPageController::class, 'home'])->name('home');
Route::get('/stories', [MarketingPageController::class, 'stories'])->name('stories');
Route::get('/insights', [MarketingPageController::class, 'insights'])->name('insights');
Route::get('/mba-jobs', [MarketingPageController::class, 'mbaJobs'])->name('mba.jobs');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/network-health', [NetworkHealthController::class, 'show'])->name('network-health');
Route::post('/network-health', [NetworkHealthController::class, 'analyze'])->name('network-health.analyze');
Route::get('/pricing', PricingController::class)->name('pricing');

Route::middleware('auth')->group(function () {
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancelled', [SubscriptionController::class, 'cancelled'])->name('subscription.cancelled');
});

Route::get('/admin', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.coffee-chats.index');
        }

        abort(403);
    }

    session(['url.intended' => route('admin.coffee-chats.index')]);

    return view('auth.login', [
        'adminPrompt' => 'Enter the admin credentials (login: admin, password: admin) to access the dashboard.',
    ]);
})->name('admin.login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/theme', [DashboardController::class, 'updateTheme'])->name('dashboard.theme');
    Route::post('/dashboard/search', [DashboardController::class, 'updateSearchSettings'])->name('dashboard.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('workspace')->name('workspace.')->middleware(['auth'])->group(function () {
    Route::redirect('/', '/workspace/coffee-chats');
    Route::get('team-finder/search', [WorkspaceTeamFinderController::class, 'teamFinder'])->name('team-finder.search');
    Route::post('team-finder/follow', [WorkspaceTeamFinderController::class, 'follow'])->name('team-finder.follow');
    Route::resource('coffee-chats', WorkspaceCoffeeChatController::class)->except(['show']);
    Route::get('analytics', [WorkspaceAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('calendar', [WorkspaceCoffeeChatController::class, 'calendar'])->name('coffee-chats.calendar');
    Route::get('coffee-chats/{coffeeChat}/ics', [WorkspaceCoffeeChatController::class, 'ics'])->name('coffee-chats.ics');
    Route::get('profile', [WorkspaceProfileController::class, 'edit'])->name('profile');
    Route::get('team-finder', [WorkspaceTeamFinderController::class, 'index'])->name('team-finder.index');
    Route::post('team-finder/{contact}/coffee-chats', [WorkspaceTeamFinderController::class, 'storeCoffeeChat'])->name('team-finder.coffee-chats.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.coffee-chats.index'))->name('dashboard');
    Route::resource('posts', AdminPostController::class);
    Route::resource('coffee-chats', AdminCoffeeChatController::class);
    Route::resource('companies', AdminCompanyController::class);
    Route::resource('contacts', AdminContactController::class);
    Route::resource('channels', AdminChannelController::class)->except(['show']);
    Route::resource('pages', AdminPageController::class);
    Route::resource('pages.components', AdminPageComponentController::class)->except(['show']);
    Route::get('seo', [SeoManagerController::class, 'index'])->name('seo.index');
    Route::get('seo/{type}/{id}/edit', [SeoManagerController::class, 'edit'])->name('seo.edit');
    Route::put('seo/{type}/{id}', [SeoManagerController::class, 'update'])->name('seo.update');
    Route::resource('workspace-fields', AdminWorkspaceFieldController::class)->except(['show']);
    Route::get('network-health', [AdminNetworkHealthAssessmentController::class, 'index'])->name('network-health.index');
    Route::get('stripe', [SubscriptionController::class, 'settings'])->name('stripe.settings');
    Route::put('stripe', [SubscriptionController::class, 'updateSettings'])->name('stripe.settings.update');
    Route::get('menu-items', [AdminWorkspaceMenuController::class, 'index'])->name('menu.index');
    Route::post('menu-items', [AdminWorkspaceMenuController::class, 'store'])->name('menu.store');
    Route::delete('menu-items/{menuItem}', [AdminWorkspaceMenuController::class, 'destroy'])->name('menu.destroy');
    Route::get('site-menu', [AdminSiteMenuController::class, 'index'])->name('site-menu.index');
    Route::post('site-menu', [AdminSiteMenuController::class, 'store'])->name('site-menu.store');
    Route::put('site-menu/{menuItem}', [AdminSiteMenuController::class, 'update'])->name('site-menu.update');
    Route::delete('site-menu/{menuItem}', [AdminSiteMenuController::class, 'destroy'])->name('site-menu.destroy');
});

require __DIR__.'/auth.php';

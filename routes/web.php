<?php

use App\Http\Controllers\Admin\ChannelController as AdminChannelController;
use App\Http\Controllers\Admin\CoffeeChatController as AdminCoffeeChatController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PageComponentController as AdminPageComponentController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SeoMetaController as AdminSeoMetaController;
use App\Http\Controllers\Admin\WorkspaceFieldController as AdminWorkspaceFieldController;
use App\Http\Controllers\MarketingPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Workspace\CoffeeChatController as WorkspaceCoffeeChatController;
use App\Http\Controllers\Workspace\AnalyticsController as WorkspaceAnalyticsController;
use App\Http\Controllers\Workspace\ProfileController as WorkspaceProfileController;
use App\Http\Controllers\Workspace\TeamFinderController as WorkspaceTeamFinderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingPageController::class, 'home'])->name('home');
Route::get('/stories', [MarketingPageController::class, 'stories'])->name('stories');
Route::get('/insights', [MarketingPageController::class, 'insights'])->name('insights');
Route::get('/mba-jobs', [MarketingPageController::class, 'mbaJobs'])->name('mba.jobs');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

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

Route::get('/dashboard', function () {
    return redirect()->route('workspace.coffee-chats.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::redirect('/dashboard', '/workspace/coffee-chats');
});

Route::prefix('workspace')->name('workspace.')->middleware(['auth'])->group(function () {
    Route::redirect('/', '/workspace/coffee-chats');
    Route::resource('coffee-chats', WorkspaceCoffeeChatController::class)->except(['show']);
    Route::get('analytics', [WorkspaceAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('calendar', [WorkspaceCoffeeChatController::class, 'calendar'])->name('coffee-chats.calendar');
    Route::get('coffee-chats/{coffeeChat}/ics', [WorkspaceCoffeeChatController::class, 'ics'])->name('coffee-chats.ics');
    Route::get('profile', [WorkspaceProfileController::class, 'edit'])->name('profile');
    Route::get('team-finder', [WorkspaceTeamFinderController::class, 'index'])->name('team-finder.index');
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
    Route::resource('seo', AdminSeoMetaController::class)->except(['show']);
    Route::resource('workspace-fields', AdminWorkspaceFieldController::class)->except(['show']);
});

require __DIR__.'/auth.php';

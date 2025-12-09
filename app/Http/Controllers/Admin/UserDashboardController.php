<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingTouchpoint;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $new7 = User::where('created_at', '>=', now()->subDays(7))->count();
        $new30 = User::where('created_at', '>=', now()->subDays(30))->count();

        $byProvider = User::query()
            ->selectRaw('COALESCE(oauth_provider, "password") as provider, count(*) as total')
            ->groupBy('provider')
            ->orderByDesc('total')
            ->get();

        $byDay = User::query()
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topSources = MarketingTouchpoint::query()
            ->whereNotNull('user_id')
            ->selectRaw('COALESCE(source, "direct") as source, COALESCE(medium, "none") as medium, count(*) as total')
            ->groupBy('source', 'medium')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $recent = User::query()
            ->latest()
            ->select(['id', 'name', 'email', 'oauth_provider', 'created_at'])
            ->limit(12)
            ->get();

        return view('admin.users.dashboard', [
            'totalUsers' => $totalUsers,
            'new7' => $new7,
            'new30' => $new30,
            'byProvider' => $byProvider,
            'byDay' => $byDay,
            'topSources' => $topSources,
            'recent' => $recent,
        ]);
    }
}

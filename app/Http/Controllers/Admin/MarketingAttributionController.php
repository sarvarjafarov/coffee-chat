<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingAttribution;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MarketingAttributionController extends Controller
{
    public function index(): View
    {
        $summary = MarketingAttribution::query()
            ->select(
                'model',
                'source',
                'medium',
                'campaign',
                DB::raw('SUM(credit) as total_credit'),
                DB::raw('COUNT(DISTINCT marketing_event_id) as conversions')
            )
            ->groupBy('model', 'source', 'medium', 'campaign')
            ->orderByDesc('total_credit')
            ->limit(100)
            ->get();

        $recent = MarketingAttribution::query()
            ->with('event')
            ->latest('occurred_at')
            ->take(20)
            ->get();

        return view('admin.analytics.attribution', [
            'summary' => $summary,
            'recent' => $recent,
        ]);
    }
}

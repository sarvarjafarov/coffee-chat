<?php

namespace Database\Seeders;

use App\Models\SiteMenuItem;
use Illuminate\Database\Seeder;

class SiteMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        if (SiteMenuItem::count() > 0) {
            return;
        }

        $links = [
            ['label' => 'Platform', 'url' => route('home'), 'sort_order' => 0],
            ['label' => 'Solutions', 'url' => route('stories'), 'sort_order' => 10],
            ['label' => 'Insights', 'url' => route('insights'), 'sort_order' => 20],
            ['label' => 'Pricing', 'url' => route('pricing'), 'sort_order' => 30],
            ['label' => 'Network health', 'url' => route('network-health'), 'sort_order' => 40],
            ['label' => 'MBA full-time jobs', 'url' => route('mba.jobs'), 'sort_order' => 50],
        ];

        foreach ($links as $link) {
            SiteMenuItem::create($link);
        }
    }
}

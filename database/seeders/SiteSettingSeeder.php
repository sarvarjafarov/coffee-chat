<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'accent_start' => '#0ea5e9',
            'accent_end' => '#2563eb',
            'surface' => '#f4fbff',
            'surface_alt' => '#e6f6ff',
            'text_primary' => '#0f172a',
            'text_muted' => '#475569',
            'footer_headline' => 'Scale your relationship programs',
            'footer_subheadline' => 'CoffeeChat OS becomes the command centre for intros, notes, and follow-through.',
            'footer_note' => 'Crafted for ambitious connectors. © ' . now()->year,
            'google_search_api_key' => env('GOOGLE_SEARCH_API_KEY'),
            'google_cse_id' => env('GOOGLE_CSE_ID'),
        ];

        foreach ($defaults as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

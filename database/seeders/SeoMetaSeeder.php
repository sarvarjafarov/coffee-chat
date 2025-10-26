<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    public function run(): void
    {
        $homePage = Page::query()->firstWhere('slug', 'home');
        $storiesPage = Page::query()->firstWhere('slug', 'stories');
        $insightsPage = Page::query()->firstWhere('slug', 'insights');

        SeoMeta::updateOrCreate(
            ['slug' => 'home'],
            [
                'page_id' => $homePage?->id,
                'title' => 'CoffeeChat OS — Orchestrate world-class coffee chats',
                'description' => 'CoffeeChat OS helps ambitious connectors plan, run, and follow up on every coffee chat with AI precision and automated workflows.',
                'keywords' => 'coffee chat tracker, networking CRM, informational interview, career switch',
                'canonical_url' => url('/'),
                'og_title' => 'Orchestrate world-class coffee chats with CoffeeChat OS',
                'og_description' => 'Track outreach, document insights, and automate follow-through to turn conversations into opportunities.',
                'og_image' => null,
                'twitter_card' => 'summary_large_image',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'stories'],
            [
                'page_id' => $storiesPage?->id,
                'title' => 'Stories & Playbooks — CoffeeChat OS',
                'description' => 'Success stories, scripts, and outreach playbooks from operators who turned conversations into offers and partnerships.',
                'keywords' => 'coffee chat stories, outreach scripts, networking playbook',
                'canonical_url' => url('/stories'),
                'og_title' => 'Stories & Playbooks from the CoffeeChat OS community',
                'og_description' => 'Explore real narratives, frameworks, and interview scripts to upgrade your coffee chat strategy.',
                'og_image' => null,
                'twitter_card' => 'summary_large_image',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'insights'],
            [
                'page_id' => $insightsPage?->id,
                'title' => 'Insights Dashboard — CoffeeChat OS Trends',
                'description' => 'Track networking benchmarks, channel lift, and response metrics across the CoffeeChat OS community.',
                'keywords' => 'networking benchmarks, outreach analytics, coffee chat insights',
                'canonical_url' => url('/insights'),
                'og_title' => 'CoffeeChat OS Insights Dashboard',
                'og_description' => 'See macro trends across channels, industries, and follow-up success rates.',
                'og_image' => null,
                'twitter_card' => 'summary_large_image',
            ]
        );
    }
}

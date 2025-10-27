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
                'meta_tags' => [
                    ['name' => 'robots', 'content' => 'index,follow'],
                    ['name' => 'author', 'content' => 'CoffeeChat OS'],
                    ['property' => 'og:locale', 'content' => 'en_US'],
                ],
                'media' => [
                    [
                        'type' => 'open_graph',
                        'url' => url('/images/share/home-og.png'),
                        'alt' => 'CoffeeChat OS hero illustration',
                        'mime_type' => 'image/png',
                        'width' => 1200,
                        'height' => 630,
                    ],
                    [
                        'type' => 'twitter',
                        'url' => url('/images/share/home-twitter.png'),
                        'alt' => 'CoffeeChat OS platform preview',
                    ],
                    [
                        'type' => 'icon',
                        'url' => url('/favicon.ico'),
                        'mime_type' => 'image/x-icon',
                    ],
                ],
                'schema' => [
                    [
                        'type' => 'application/ld+json',
                        'payload' => [
                            '@context' => 'https://schema.org',
                            '@type' => 'Organization',
                            'name' => 'CoffeeChat OS',
                            'url' => url('/'),
                            'logo' => url('/images/brand/logo-mark.png'),
                            'sameAs' => [
                                'https://www.linkedin.com/company/coffeechat-os',
                                'https://twitter.com/coffeechatos',
                            ],
                        ],
                    ],
                ],
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
                'meta_tags' => [
                    ['name' => 'robots', 'content' => 'index,follow'],
                ],
                'media' => [
                    [
                        'type' => 'open_graph',
                        'url' => url('/images/share/stories-og.png'),
                        'alt' => 'CoffeeChat OS stories collection',
                        'mime_type' => 'image/png',
                    ],
                ],
                'schema' => [
                    [
                        'type' => 'application/ld+json',
                        'payload' => [
                            '@context' => 'https://schema.org',
                            '@type' => 'CollectionPage',
                            'name' => 'Stories & Playbooks — CoffeeChat OS',
                            'description' => 'Success stories and playbooks from CoffeeChat OS members.',
                            'url' => url('/stories'),
                        ],
                    ],
                ],
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
                'meta_tags' => [
                    ['name' => 'robots', 'content' => 'index,follow'],
                ],
                'media' => [
                    [
                        'type' => 'open_graph',
                        'url' => url('/images/share/insights-og.png'),
                        'alt' => 'CoffeeChat OS insights analytics preview',
                        'mime_type' => 'image/png',
                    ],
                ],
                'schema' => [
                    [
                        'type' => 'application/ld+json',
                        'payload' => [
                            '@context' => 'https://schema.org',
                            '@type' => 'WebPage',
                            'name' => 'Insights Dashboard — CoffeeChat OS',
                            'description' => 'Networking benchmarks, channel lift, and response metrics across the CoffeeChat OS community.',
                            'url' => url('/insights'),
                        ],
                    ],
                ],
            ]
        );
    }
}

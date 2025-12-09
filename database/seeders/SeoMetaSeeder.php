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
        $pricingPage = Page::query()->firstWhere('slug', 'pricing');
        $networkHealthPage = Page::query()->firstWhere('slug', 'network-health');
        $mbaJobsPage = Page::query()->firstWhere('slug', 'mba-jobs');

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

        SeoMeta::updateOrCreate(
            ['slug' => 'pricing'],
            [
                'page_id' => $pricingPage?->id,
                'title' => 'Pricing | CoffeeChat OS Plans',
                'description' => 'Start free to log coffee chats, then upgrade for unlimited flows, priority support, and advanced analytics.',
                'canonical_url' => url('/pricing'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'network-health'],
            [
                'page_id' => $networkHealthPage?->id,
                'title' => 'Network Health Check | CoffeeChat OS',
                'description' => 'Run a quick audit to see your strongest channels, gaps, and next steps to improve coffee chat momentum.',
                'canonical_url' => url('/network-health'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'mba-jobs'],
            [
                'page_id' => $mbaJobsPage?->id,
                'title' => 'MBA Job Board | CoffeeChat OS Opportunities',
                'description' => 'Curated MBA full-time and internship roles with outreach-ready details to start new conversations.',
                'canonical_url' => url('/mba-jobs'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'login'],
            [
                'title' => 'Log In | CoffeeChat OS',
                'description' => 'Sign in with Google or LinkedIn to manage coffee chats, follow-ups, and outreach in one workspace.',
                'canonical_url' => url('/login'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'register'],
            [
                'title' => 'Create Your Workspace | CoffeeChat OS',
                'description' => 'Spin up CoffeeChat OS for your team—log chats, track follow-ups, and hit weekly networking goals.',
                'canonical_url' => url('/register'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'forgot-password'],
            [
                'title' => 'Reset Password | CoffeeChat OS',
                'description' => 'Request a reset link to get back into your CoffeeChat OS workspace.',
                'canonical_url' => url('/forgot-password'),
                'twitter_card' => 'summary',
            ]
        );

        // Workspace (authenticated) pages
        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/coffee-chats'],
            [
                'title' => 'My Coffee Chats | CoffeeChat OS Workspace',
                'description' => 'Log conversations, capture notes, and track status, streaks, and weekly goals.',
                'canonical_url' => url('/workspace/coffee-chats'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/coffee-chats/create'],
            [
                'title' => 'Log a Coffee Chat | CoffeeChat OS Workspace',
                'description' => 'Record a new coffee chat with company, contact, notes, and next steps.',
                'canonical_url' => url('/workspace/coffee-chats/create'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/coffee-chats/edit'],
            [
                'title' => 'Update Coffee Chat | CoffeeChat OS Workspace',
                'description' => 'Refresh notes, status, and follow-up actions for this conversation.',
                'canonical_url' => url('/workspace/coffee-chats'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/calendar'],
            [
                'title' => 'Coffee Chat Calendar | CoffeeChat OS Workspace',
                'description' => 'See your scheduled coffee chats and export ICS invites.',
                'canonical_url' => url('/workspace/calendar'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/team-finder'],
            [
                'title' => 'Team Finder | CoffeeChat OS Workspace',
                'description' => 'Filter your saved network by role, company, and team. Jump to LinkedIn or follow for a coffee chat.',
                'canonical_url' => url('/workspace/team-finder'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/analytics'],
            [
                'title' => 'Analytics | CoffeeChat OS Workspace',
                'description' => 'Status mix, channel mix, and custom field insights across your logged coffee chats.',
                'canonical_url' => url('/workspace/analytics'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/mock-interviews'],
            [
                'title' => 'Mock Interviews | CoffeeChat OS Workspace',
                'description' => 'Schedule and track mock interviews with notes and ICS exports.',
                'canonical_url' => url('/workspace/mock-interviews'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/cases'],
            [
                'title' => 'Case Practice | CoffeeChat OS Workspace',
                'description' => 'Create and edit case practice sessions to prep for interviews.',
                'canonical_url' => url('/workspace/cases'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'workspace/profile'],
            [
                'title' => 'Profile | CoffeeChat OS Workspace',
                'description' => 'Manage your profile, preferences, and weekly coffee chat goals.',
                'canonical_url' => url('/workspace/profile'),
                'twitter_card' => 'summary',
            ]
        );

        // Admin
        SeoMeta::updateOrCreate(
            ['slug' => 'admin'],
            [
                'title' => 'Admin Dashboard | CoffeeChat OS',
                'description' => 'Manage coffee chats, posts, pages, channels, and SEO settings.',
                'canonical_url' => url('/admin'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/coffee-chats'],
            [
                'title' => 'Admin | Coffee Chats',
                'description' => 'Review and manage all coffee chat records across the workspace.',
                'canonical_url' => url('/admin/coffee-chats'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/posts'],
            [
                'title' => 'Admin | Posts & Stories',
                'description' => 'Publish and edit stories, insights, and marketing content.',
                'canonical_url' => url('/admin/posts'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/pages'],
            [
                'title' => 'Admin | Pages & Components',
                'description' => 'Edit landing pages and sections powering the marketing site.',
                'canonical_url' => url('/admin/pages'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/channels'],
            [
                'title' => 'Admin | Channels',
                'description' => 'Manage outreach channels used across coffee chats.',
                'canonical_url' => url('/admin/channels'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/contacts'],
            [
                'title' => 'Admin | Contacts',
                'description' => 'Maintain contacts linked to coffee chats.',
                'canonical_url' => url('/admin/contacts'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/companies'],
            [
                'title' => 'Admin | Companies',
                'description' => 'Maintain companies linked to coffee chats.',
                'canonical_url' => url('/admin/companies'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/seo'],
            [
                'title' => 'Admin | SEO Settings',
                'description' => 'Control meta titles, descriptions, and share images.',
                'canonical_url' => url('/admin/seo'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/workspace-fields'],
            [
                'title' => 'Admin | Workspace Fields',
                'description' => 'Define custom fields captured on coffee chats and analytics.',
                'canonical_url' => url('/admin/workspace-fields'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/network-health'],
            [
                'title' => 'Admin | Network Health',
                'description' => 'Monitor health and diagnostics for network assessments.',
                'canonical_url' => url('/admin/network-health'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/attribution'],
            [
                'title' => 'Admin | Attribution',
                'description' => 'Review first, last, linear, and time-decay credit by source, medium, and campaign.',
                'canonical_url' => url('/admin/attribution'),
                'twitter_card' => 'summary',
            ]
        );

        SeoMeta::updateOrCreate(
            ['slug' => 'admin/feedback'],
            [
                'title' => 'Admin | Feedback Inbox',
                'description' => 'See user-reported bugs, friction, and ideas collected across the product.',
                'canonical_url' => url('/admin/feedback'),
                'twitter_card' => 'summary',
            ]
        );
    }
}

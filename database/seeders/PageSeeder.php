<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageComponent;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $home = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'name' => 'Home',
                'description' => 'CoffeeChat OS marketing homepage',
            ]
        );

        $this->seedHomeComponents($home);

        $stories = Page::updateOrCreate(
            ['slug' => 'stories'],
            [
                'name' => 'Stories',
                'description' => 'Content hub for success stories and playbooks',
            ]
        );

        $this->seedStoriesComponents($stories);

        $insights = Page::updateOrCreate(
            ['slug' => 'insights'],
            [
                'name' => 'Insights',
                'description' => 'Analytics overview and trend highlights',
            ]
        );

        $this->seedInsightsComponents($insights);
    }

    protected function seedHomeComponents(Page $page): void
    {
        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'hero'],
            [
                'title' => 'Orchestrate world-class coffee chats with AI precision.',
                'subtitle' => 'Power every informational interview with structured prep, live intelligence, and automated follow-through. CoffeeChat OS is the scale engine for relationship builders.',
                'style' => [
                    'hero' => [
                        'background' => 'radial-gradient(circle at 20% 10%, rgba(99,102,241,0.25), transparent 55%), radial-gradient(circle at 80% 0%, rgba(20,184,166,0.2), transparent 50%), rgba(9, 10, 32, 0.82)',
                        'overlay' => 'linear-gradient(135deg, rgba(255,255,255,0.08), transparent 60%)',
                        'heading_color' => '#ffffff',
                        'subtitle_color' => 'rgba(226,232,240,0.75)',
                        'primary_button' => [
                            'background' => 'linear-gradient(135deg, #6366ff 0%, #7c3aed 40%, #14b8a6 100%)',
                            'color' => '#ffffff',
                        ],
                        'secondary_button' => [
                            'color' => '#f8fafc',
                            'border' => 'rgba(255,255,255,0.4)',
                        ],
                        'stats' => [
                            'background' => 'rgba(255,255,255,0.04)',
                            'border' => 'rgba(255,255,255,0.05)',
                            'value_color' => '#ffffff',
                            'label_color' => 'rgba(226,232,240,0.65)',
                        ],
                        'channels' => [
                            'title_color' => 'rgba(226,232,240,0.7)',
                            'badge_background' => 'rgba(255,255,255,0.08)',
                            'badge_color' => '#f8fafc',
                            'badge_border' => 'rgba(255,255,255,0.12)',
                        ],
                    ],
                ],
                'meta' => [
                    'badge' => 'Engineered for connectors',
                    'primary_button' => [
                        'label' => 'Get started',
                        'url' => '/register',
                        'icon' => 'mdi-rocket-launch-outline',
                    ],
                    'secondary_button' => [
                        'label' => 'Explore platform',
                        'url' => '/stories',
                        'icon' => 'mdi-play-circle-outline',
                    ],
                    'stats' => [
                        ['value' => '+2k', 'label' => 'Coffee chats logged'],
                        ['value' => '74%', 'label' => 'Faster follow-up completion'],
                        ['value' => 'A+', 'label' => 'Candidate experience rating'],
                    ],
                    'next_chat' => [
                        'title' => 'Coffee with Priya @ Stripe',
                        'schedule' => 'Wed · 9:00 AM',
                    ],
                    'timeline' => [
                        ['title' => 'Prep Deck', 'description' => 'Research stakeholders, latest moves, and key talking points.'],
                        ['title' => 'Coffee Chat', 'description' => 'Run the conversation with live prompts and intelligence.'],
                        ['title' => 'Follow-up', 'description' => 'Close the loop with notes, materials, and next steps.', 'status' => 'Due in 6 hrs'],
                    ],
                    'channels' => [
                        ['label' => 'Email', 'icon' => 'mdi-email-outline'],
                        ['label' => 'LinkedIn DM', 'icon' => 'mdi-linkedin'],
                        ['label' => 'Referral', 'icon' => 'mdi-account-group-outline'],
                    ],
                    'confidence' => [
                        'score' => '8.7 / 10',
                        'status' => 'Prep checklist complete',
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'features'],
            [
                'title' => 'CoffeeChat OS platform pillars',
                'style' => [
                    'features' => [
                        'card_background' => 'linear-gradient(160deg, rgba(99,102,241,0.18), rgba(79,70,229,0.06))',
                        'card_border_color' => 'rgba(99,102,241,0.18)',
                        'title_color' => '#ffffff',
                        'description_color' => 'rgba(226,232,240,0.7)',
                    ],
                ],
                'meta' => [
                    'features' => [
                        [
                            'icon' => 'mdi-table-furniture',
                            'title' => 'Material-first workspace',
                            'description' => 'Deploy a control room for every conversation with timeline intelligence, AI copilots, and dynamic briefs.',
                            'link_text' => 'Request a guided tour →',
                            'link_url' => '/dashboard',
                        ],
                        [
                            'icon' => 'mdi-brain',
                            'title' => 'Smart memory cues',
                            'description' => 'Automate nudges, follow-ups, and relationship health checks so momentum never slips.',
                            'footnote' => '★ 92% of operators improved follow-through within 3 weeks.',
                        ],
                        [
                            'icon' => 'mdi-chart-areaspline',
                            'title' => 'Analytics that resonate',
                            'description' => 'Visualise channel conversion, mentor leverage, and warm intro lift to focus where signal is hottest.',
                            'footnote' => 'Roadmap: native Notion & Linear sync Q1.',
                        ],
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'ritual'],
            [
                'title' => 'Blueprint your networking flywheel in four orchestrated motions.',
                'subtitle' => 'CoffeeChat OS distills the playbooks of elite relationship builders into a rhythm you can personalise, automate, and scale.',
                'style' => [
                    'ritual' => [
                        'testimonial_background' => 'linear-gradient(135deg, rgba(255,255,255,0.08), rgba(79,70,229,0.1))',
                    ],
                ],
                'meta' => [
                    'steps' => [
                        ['label' => '01 · Canvas', 'description' => 'Map prospects, companies, and mentors. Tags keep track of industries, locations, and referral strength.'],
                        ['label' => '02 · Reach', 'description' => 'Launch outreach sequences using channel formulas that get replies. Log scripts, snippets, and follow-up dates.'],
                        ['label' => '03 · Converse', 'description' => 'Capture live notes, key takeaways, and intros promised during the chat with real-time sync across devices.'],
                        ['label' => '04 · Compound', 'description' => 'Automated reminders nurture long-term relationships and surface opportunities at the perfect cadence.'],
                    ],
                    'trusted' => [
                        'title' => 'Trusted by',
                        'headline' => 'Community builders',
                        'body' => 'From MBA cohorts to engineering guilds, leaders rely on CoffeeChat OS to orchestrate programs that scale.',
                    ],
                    'testimonial' => [
                        'quote' => 'My goal was to break into product marketing within 120 days. CoffeeChat OS became my cockpit—material cards, analytics, and reminders kept me honest. I hit offer week 10.',
                        'author' => 'Jasmine Lee',
                        'role' => 'PMM @ Atlassian · ex-VC analyst',
                        'badge' => 'Offer secured',
                    ],
                    'network_health' => [
                        'title' => 'Network Health',
                        'value' => '29 active',
                        'description' => 'relationships nurtured this quarter.',
                        'progress' => 74,
                        'footnote' => '74% of target outreach completed · 5 warm intros pending.',
                        'tag' => 'Live pulse',
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'cta'],
            [
                'title' => 'Deploy CoffeeChat OS across your organisation.',
                'subtitle' => 'Work with our success architects to wire automations, briefings, and analytics around your team’s networking goals.',
                'style' => [
                    'cta' => [
                        'background' => 'linear-gradient(120deg, rgba(99,102,241,0.55), rgba(14,165,233,0.35))',
                        'overlay' => 'radial-gradient(circle at 80% 0%, rgba(226,232,240,0.25), transparent 55%)',
                        'padding' => 'clamp(2rem, 5vw, 3rem)',
                    ],
                ],
                'meta' => [
                    'primary_button' => [
                        'label' => 'Talk to sales',
                        'url' => 'mailto:hello@coffeechat.os',
                    ],
                    'secondary_button' => [
                        'label' => 'Start for free',
                        'url' => '/register',
                    ],
                ],
            ]
        );
    }

    protected function seedStoriesComponents(Page $page): void
    {
        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'hero'],
            [
                'title' => 'Stories & Playbooks',
                'subtitle' => 'Deep dives, frameworks, and interview scripts from community members who turned conversations into offers and partnerships.',
                'meta' => [
                    'cta' => [
                        'label' => 'Submit your story',
                        'url' => 'mailto:hello@coffeechat.os',
                    ],
                    'stat' => 'Updated weekly with fresh narratives.',
                ],
            ]
        );
    }

    protected function seedInsightsComponents(Page $page): void
    {
        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'hero'],
            [
                'title' => 'Insights Dashboard',
                'subtitle' => 'Macro trends across the CoffeeChat OS network. Track evolving outreach patterns, industries heating up, and response benchmarks.',
                'meta' => [
                    'chips' => ['Community trends', 'Benchmarks', 'What’s next'],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'highlights'],
            [
                'title' => 'This week’s highlights',
                'meta' => [
                    'cards' => [
                        [
                            'title' => 'Response time sweet spot',
                            'body' => 'LinkedIn voice messages received 32% faster replies than long-form emails over the past 14 days.',
                            'tag' => 'Channel intel',
                        ],
                        [
                            'title' => 'Industry on fire',
                            'body' => 'Climate tech conversations grew 21% QoQ, with hiring managers proactively requesting follow-up materials.',
                            'tag' => 'Opportunity radar',
                        ],
                        [
                            'title' => 'Intro leverage',
                            'body' => 'Mentor-led referrals convert to second conversations 2.4x faster than cold outreach in the same company.',
                            'tag' => 'Mentor advantage',
                        ],
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'metrics'],
            [
                'title' => 'Pipeline metrics',
                'meta' => [
                    'metrics' => [
                        ['label' => 'Avg. response rate', 'value' => '46%', 'change' => '+6% WoW'],
                        ['label' => 'Chats per active user', 'value' => '3.2', 'change' => '+0.4'],
                        ['label' => 'Follow-up completion', 'value' => '81%', 'change' => '+9%'],
                        ['label' => 'Warm intro lift', 'value' => '2.1x', 'change' => '▲'],
                    ],
                ],
            ]
        );
    }
}

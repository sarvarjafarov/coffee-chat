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
                        'background' => 'radial-gradient(circle at 6% -12%, rgba(56,189,248,0.26), transparent 58%), radial-gradient(circle at 94% 0%, rgba(14,165,233,0.22), transparent 60%), linear-gradient(180deg, rgba(244,251,255,0.96) 0%, rgba(255,255,255,0.92) 55%, rgba(244,251,255,0.9) 100%)',
                        'overlay' => 'repeating-linear-gradient(120deg, rgba(148,197,255,0.16) 0 3px, transparent 3px 20px)',
                        'heading_color' => '#0f172a',
                        'subtitle_color' => 'rgba(51,65,85,0.9)',
                        'primary_button' => [
                            'background' => 'linear-gradient(120deg, #0284c7 0%, #1d4ed8 100%)',
                            'color' => '#ffffff',
                        ],
                        'secondary_button' => [
                            'color' => '#1d4ed8',
                            'border' => 'rgba(148,197,255,0.55)',
                        ],
                        'stats' => [
                            'background' => 'linear-gradient(160deg, rgba(255,255,255,0.96), rgba(229,244,255,0.9))',
                            'border' => 'rgba(148,163,184,0.16)',
                            'value_color' => '#0f172a',
                            'label_color' => 'rgba(71,85,105,0.68)',
                        ],
                        'channels' => [
                            'title_color' => 'rgba(100,116,139,0.7)',
                            'badge_background' => 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(236,248,255,0.95) 100%)',
                            'badge_color' => 'rgba(51,65,85,0.9)',
                            'badge_border' => 'rgba(148,197,255,0.28)',
                        ],
                    ],
                ],
                'meta' => [
                    'badge' => 'Engineered for connectors',
                    'timeline_title' => 'Vacancy-to-coffee chat flow',
                    'timeline_description' => 'Move from open role discovery to warm introductions in one workspace.',
                    'timeline_badge' => 'User flow',
                    'primary_button' => [
                        'label' => 'Get started',
                        'url' => '/register',
                        'icon' => 'mdi-rocket-launch-outline',
                    ],
                    'secondary_button' => [
                        'label' => 'View pricing',
                        'url' => '/pricing',
                        'icon' => 'mdi-currency-usd',
                    ],
                    'stats' => [
                        ['value' => '+2k', 'label' => 'Coffee chats logged'],
                        ['value' => '74%', 'label' => 'Faster follow-up completion'],
                        ['value' => 'A+', 'label' => 'Candidate experience rating'],
                    ],
                    'next_chat' => [
                        'label' => 'Next coffee chat',
                        'title' => 'Coffee with Priya · Hiring Manager',
                        'schedule' => 'Wed · 9:00 AM',
                        'notes' => 'Review the Strategic Partnerships vacancy brief and share the team alignment doc.',
                        'link' => '/workspace/coffee-chats',
                        'cta' => 'Open workspace',
                    ],
                    'timeline' => [
                        ['title' => 'Capture active vacancy', 'description' => 'Pin an open role to your CoffeeChat flow and auto-save the company, role, and target outcomes.'],
                        ['title' => 'Spot the hiring circle', 'description' => 'Use the vacancy insights to uncover managers, recruiters, and peers tied to the role so you know who to meet.'],
                        ['title' => 'Queue coffee chats', 'description' => 'Send those people straight into your CoffeeChat flow with outreach and follow-up tasks ready.', 'status' => 'Next up'],
                    ],
                    'channels' => [
                        ['label' => 'Email', 'icon' => 'mdi-email-outline'],
                        ['label' => 'LinkedIn DM', 'icon' => 'mdi-linkedin'],
                        ['label' => 'Referral', 'icon' => 'mdi-account-group-outline'],
                    ],
                    'pills' => [
                        ['icon' => 'mdi-briefcase-search-outline', 'label' => 'Vacancy tracker'],
                        ['icon' => 'mdi-account-search-outline', 'label' => 'Hiring circle intel'],
                        ['icon' => 'mdi-account-multiple-plus-outline', 'label' => 'Team Finder picks'],
                        ['icon' => 'mdi-flash', 'label' => 'Coffee chat copilots'],
                    ],
                    'confidence' => [
                        'title' => 'Confidence pulse',
                        'score' => '8.7 / 10',
                        'status' => 'Prep checklist complete',
                        'caption' => 'AI copilots queued 2 follow-ups for today.',
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'features'],
            [
                'title' => 'CoffeeChat OS platform pillars',
                'subtitle' => 'Move from vacancy discovery to warm introductions with one connected workflow.',
                'style' => [
                    'features' => [
                        'card_background' => 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(239,247,255,0.93) 100%)',
                        'card_border_color' => 'rgba(148,197,255,0.22)',
                        'title_color' => '#0f172a',
                        'description_color' => 'rgba(71,85,105,0.82)',
                        'icon_background' => 'rgba(37,99,235,0.12)',
                        'icon_border' => 'rgba(59,130,246,0.18)',
                        'icon_color' => 'rgba(37,99,235,0.78)',
                        'link_color' => '#1d4ed8',
                    ],
                ],
                'meta' => [
                    'eyebrow' => 'Platform pillars',
                    'cta_link' => [
                        'label' => 'See the flow in action',
                        'url' => '/stories',
                    ],
                    'features' => [
                        [
                            'icon' => 'mdi-briefcase-check-outline',
                            'title' => 'Vacancy capture workspace',
                            'description' => 'Clip any open role into CoffeeChat, complete with company insights, deadline reminders, and talking points in one place.',
                            'link_text' => 'Request a guided tour →',
                            'link_url' => '/dashboard',
                        ],
                        [
                            'icon' => 'mdi-account-search-outline',
                            'title' => 'Hiring circle radar',
                            'description' => 'Surface managers, peers, and cross-functional partners tied to the vacancy so you know exactly who to meet next.',
                            'footnote' => '★ 92% of operators lined up stakeholder chats within 3 weeks.',
                        ],
                        [
                            'icon' => 'mdi-account-multiple-plus-outline',
                            'title' => 'Team Finder autopilot',
                            'description' => 'Drop those people straight into your CoffeeChat flow with outreach templates, AI follow-ups, and shared notes ready.',
                            'footnote' => 'Roadmap: auto-sync outreach status across your hiring pods.',
                        ],
                    ],
                ],
            ]
        );

        PageComponent::updateOrCreate(
            ['page_id' => $page->id, 'key' => 'ritual'],
            [
                'title' => 'Turn open vacancies into warm conversations in four steps.',
                'subtitle' => 'CoffeeChat OS links vacancy tracking, team discovery, and outreach so momentum compounds automatically.',
                'style' => [
                    'ritual' => [
                        'testimonial_background' => 'linear-gradient(135deg, rgba(255,255,255,0.08), rgba(79,70,229,0.1))',
                    ],
                ],
                'meta' => [
                    'eyebrow' => 'User flow',
                    'steps' => [
                        ['label' => '01 · Capture vacancy', 'description' => 'Add an active vacancy to your CoffeeChat flow. Role context, notes, and deadlines stay linked to every outreach.'],
                        ['label' => '02 · Map the hiring team', 'description' => 'Leverage vacancy insights to identify managers, peers, recruiters, and alumni who influence the decision.'],
                        ['label' => '03 · Queue warm chats', 'description' => 'Use Team Finder to drop those people into your CoffeeChat flow with templates, reminders, and shared notes.'],
                        ['label' => '04 · Accelerate outcomes', 'description' => 'Track meetings, follow-ups, and referrals tied to each vacancy so momentum never slips.'],
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
                        'label' => 'View pricing',
                        'url' => '/pricing',
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

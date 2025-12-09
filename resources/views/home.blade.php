@extends('layouts.site')

@section('content')
    @php
        $hero = $components['hero'] ?? null;
        $heroMeta = $hero?->meta ?? [];
        $heroBadge = data_get($heroMeta, 'badge');
        $heroStats = collect(data_get($heroMeta, 'stats', []));
        $heroTimeline = collect(data_get($heroMeta, 'timeline', []));
        $heroTimelineItems = $heroTimeline->isNotEmpty() ? $heroTimeline : collect([
            ['title' => 'Capture active vacancy', 'description' => 'Pin an open role to your CoffeeChat flow and auto-save the company, role, and target outcomes.'],
            ['title' => 'Spot the hiring circle', 'description' => 'Use the vacancy insights to uncover managers, recruiters, and peers tied to the role so you know who to meet.'],
            ['title' => 'Queue coffee chats', 'description' => 'Send those people straight into your CoffeeChat flow with outreach and follow-up tasks ready.', 'status' => 'Next up'],
        ]);
        $heroTimelineTitle = data_get($heroMeta, 'timeline_title', 'Vacancy-to-coffee chat flow');
        $heroTimelineDescription = data_get($heroMeta, 'timeline_description', 'Move from open role discovery to warm introductions in one workspace.');
        $heroTimelineBadge = data_get($heroMeta, 'timeline_badge', 'User flow');
        $heroChannels = collect(data_get($heroMeta, 'channels', []));
        $heroPrimaryButton = data_get($heroMeta, 'primary_button', ['label' => 'Get started', 'url' => '/register']);
        $heroSecondaryButton = data_get($heroMeta, 'secondary_button', ['label' => 'View pricing', 'url' => '/pricing']);
        $heroRating = data_get($heroMeta, 'rating', []);
        $heroRatingStars = max(1, (int) data_get($heroRating, 'stars', 5));
        $heroRatingText = data_get($heroRating, 'text', 'Join 2,500+ connectors who trust CoffeeChat OS');
        $heroRatingCaption = data_get($heroRating, 'caption', 'Rated 5.0 by program leads.');
        $heroPillItems = collect(data_get($heroMeta, 'pills', []));
        if ($heroPillItems->isEmpty()) {
            $heroPillItems = collect([
                ['icon' => 'mdi-briefcase-search-outline', 'label' => 'Vacancy tracker'],
                ['icon' => 'mdi-account-search-outline', 'label' => 'Hiring circle intel'],
                ['icon' => 'mdi-account-multiple-plus-outline', 'label' => 'Team Finder picks'],
                ['icon' => 'mdi-flash', 'label' => 'Coffee chat copilots'],
            ]);
        }
        $heroStats = $heroStats->isNotEmpty() ? $heroStats : collect([
            ['value' => '+2k', 'label' => 'Coffee chats logged'],
            ['value' => '74%', 'label' => 'Faster follow-up completion'],
        ]);
        $heroLogos = $heroChannels->take(8);
        $nextChat = collect(data_get($heroMeta, 'next_chat', [
            'label' => 'Next coffee chat',
            'title' => 'Coffee with Priya · Hiring Manager',
            'schedule' => 'Wed · 9:00 AM',
            'notes' => 'Review notes on the Strategic Partnerships vacancy and bring the team alignment doc.',
            'link' => '/workspace/coffee-chats',
            'cta' => 'Open workspace',
        ]));
        $heroConfidence = collect(data_get($heroMeta, 'confidence', []));
        if ($heroConfidence->isEmpty()) {
            $heroConfidence = collect([
                'title' => 'Confidence pulse',
                'score' => '8.7 / 10',
                'status' => 'Prep checklist complete',
                'caption' => 'AI copilots queued 2 follow-ups for today.',
            ]);
        }
        $showNextChatCard = filled($nextChat->get('title')) || filled($nextChat->get('schedule')) || filled($nextChat->get('notes')) || filled($nextChat->get('link'));
        $showConfidenceCard = filled($heroConfidence->get('score')) || filled($heroConfidence->get('status')) || filled($heroConfidence->get('caption'));
        $heroHasWorkflow = $heroTimelineItems->isNotEmpty() || $showNextChatCard || $showConfidenceCard;

        $featureComponent = $components['features'] ?? null;
        $featureItems = collect(data_get($featureComponent?->meta, 'features', []));
        $featureEyebrow = data_get($featureComponent?->meta, 'eyebrow', 'Platform pillars');
        $featureSubtitle = $featureComponent?->subtitle ?? 'Move from vacancy discovery to warm introductions with one connected workflow.';
        $featureLink = data_get($featureComponent?->meta, 'cta_link');
        if (! $featureLink) {
            $featureLink = ['label' => 'See the flow in action', 'url' => route('stories')];
        }

        $ritual = $components['ritual'] ?? null;
        $ritualMeta = $ritual?->meta ?? [];
        $ritualEyebrow = data_get($ritualMeta, 'eyebrow', 'User flow');
        $ritualSteps = collect(data_get($ritualMeta, 'steps', []));
        $trusted = data_get($ritualMeta, 'trusted', []);
        $testimonial = data_get($ritualMeta, 'testimonial', []);
        $networkHealth = data_get($ritualMeta, 'network_health', []);

        $cta = $components['cta'] ?? null;
        $ctaMeta = $cta?->meta ?? [];
        $ctaPrimary = data_get($ctaMeta, 'primary_button', ['label' => 'Talk to us', 'url' => 'mailto:hello@coffeechat.os']);
        $ctaSecondary = data_get($ctaMeta, 'secondary_button', ['label' => 'View pricing', 'url' => '/pricing']);

        $heroStyle = data_get($hero?->style, 'hero', []);
        $featuresStyle = data_get($featureComponent?->style, 'features', []);
        $ritualStyle = data_get($ritual?->style, 'ritual', []);
        $ctaStyle = data_get($cta?->style, 'cta', []);

        $styleToCssVars = function (array $map): string {
            return collect($map)
                ->filter(static fn ($value) => filled($value))
                ->map(static fn ($value, $key) => $key . ':' . $value)
                ->implode(';');
        };

        $buildStyleAttr = function (string $vars, string $append = ''): string {
            $style = $vars;
            if ($append !== '') {
                $style = $style !== '' ? $style . ';' . $append : $append;
            }

            return trim($style, '; ');
        };

        $heroSectionStyle = $buildStyleAttr($styleToCssVars([
            '--hero-background' => data_get($heroStyle, 'background') ?: 'radial-gradient(circle at 6% -12%, rgba(56,189,248,0.26), transparent 58%), radial-gradient(circle at 94% 0%, rgba(14,165,233,0.22), transparent 60%), linear-gradient(180deg, var(--surface, #f4fbff) 0%, var(--surface-alt, #e6f6ff) 55%, #ffffff 100%)',
            '--hero-overlay' => data_get($heroStyle, 'overlay') ?: 'repeating-linear-gradient(120deg, rgba(148,197,255,0.16) 0 3px, transparent 3px 20px)',
            '--hero-heading-color' => data_get($heroStyle, 'heading_color') ?: '#0f172a',
            '--hero-subtitle-color' => data_get($heroStyle, 'subtitle_color') ?: 'rgba(51,65,85,0.9)',
            '--hero-primary-bg' => data_get($heroStyle, 'primary_button.background') ?: 'linear-gradient(120deg, #0284c7 0%, #1d4ed8 100%)',
            '--hero-primary-color' => data_get($heroStyle, 'primary_button.color') ?: '#ffffff',
            '--hero-secondary-color' => data_get($heroStyle, 'secondary_button.color') ?: '#1d4ed8',
            '--hero-secondary-border' => data_get($heroStyle, 'secondary_button.border') ?: 'rgba(148,197,255,0.55)',
            '--hero-stats-background' => data_get($heroStyle, 'stats.background') ?: 'linear-gradient(160deg, rgba(255,255,255,0.96), rgba(229,244,255,0.9))',
            '--hero-stats-border' => data_get($heroStyle, 'stats.border') ?: 'rgba(148,163,184,0.16)',
            '--hero-stats-value-color' => data_get($heroStyle, 'stats.value_color') ?: '#0f172a',
            '--hero-stats-label-color' => data_get($heroStyle, 'stats.label_color') ?: 'rgba(71,85,105,0.68)',
            '--hero-tagline-color' => 'rgba(88,119,165,0.7)',
            '--hero-pill-border' => 'rgba(148,197,255,0.28)',
            '--hero-pill-background' => 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(236,248,255,0.94) 100%)',
            '--hero-pill-icon-bg' => 'rgba(148,197,255,0.18)',
            '--hero-pill-icon-border' => 'rgba(59,130,246,0.24)',
            '--hero-channels-title-color' => data_get($heroStyle, 'channels.title_color') ?: 'rgba(100,116,139,0.7)',
            '--hero-channels-badge-background' => data_get($heroStyle, 'channels.badge_background') ?: 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(236,248,255,0.95) 100%)',
            '--hero-channels-badge-color' => data_get($heroStyle, 'channels.badge_color') ?: 'rgba(51,65,85,0.9)',
            '--hero-channels-badge-border' => data_get($heroStyle, 'channels.badge_border') ?: 'rgba(148,197,255,0.28)',
        ]), 'margin-left:auto;margin-right:auto');

        $featuresSectionStyle = $buildStyleAttr($styleToCssVars([
            '--feature-card-background' => data_get($featuresStyle, 'card_background') ?: 'linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(239,247,255,0.93) 100%)',
            '--feature-card-border' => data_get($featuresStyle, 'card_border_color') ?: 'rgba(148,197,255,0.22)',
            '--feature-title-color' => data_get($featuresStyle, 'title_color') ?: '#0f172a',
            '--feature-description-color' => data_get($featuresStyle, 'description_color') ?: 'rgba(71,85,105,0.82)',
            '--feature-icon-background' => data_get($featuresStyle, 'icon_background') ?: 'rgba(37,99,235,0.12)',
            '--feature-icon-border' => data_get($featuresStyle, 'icon_border') ?: 'rgba(59,130,246,0.18)',
            '--feature-icon-color' => data_get($featuresStyle, 'icon_color') ?: 'rgba(37,99,235,0.78)',
            '--feature-link-color' => data_get($featuresStyle, 'link_color') ?: '#1d4ed8',
        ]));

        $ritualSectionStyle = $buildStyleAttr($styleToCssVars([
            '--ritual-testimonial-background' => data_get($ritualStyle, 'testimonial_background') ?: 'linear-gradient(150deg, rgba(248,250,252,0.9), rgba(226,232,240,0.75))',
        ]));

        $ctaSectionStyle = $buildStyleAttr($styleToCssVars([
            '--cta-background' => data_get($ctaStyle, 'background'),
            '--cta-overlay' => data_get($ctaStyle, 'overlay'),
            '--cta-padding' => data_get($ctaStyle, 'padding'),
        ]));
    @endphp

    <style>
        .hero-simplify {
            position: relative;
            width: clamp(320px, 92vw, 1200px);
            border-radius: 48px;
            padding: clamp(4.2rem, 7vw, 6rem) clamp(2.4rem, 6vw, 4.8rem);
            background: var(
                --hero-background,
                radial-gradient(circle at 6% -12%, rgba(56,189,248,0.26), transparent 58%),
                radial-gradient(circle at 94% 0%, rgba(14,165,233,0.22), transparent 60%),
                linear-gradient(180deg, var(--surface, #f4fbff) 0%, var(--surface-alt, #e6f6ff) 55%, #ffffff 100%)
            );
            overflow: hidden;
            box-shadow: 0 34px 90px -58px rgba(15,23,42,0.2);
            border: 1px solid rgba(148,197,255,0.26);
            backdrop-filter: blur(16px);
            isolation: isolate;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 0;
        }

        .hero-simplify::before,
        .hero-simplify::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .hero-simplify::before {
            background: var(--hero-overlay, repeating-linear-gradient(120deg, rgba(148,197,255,0.18) 0 3px, transparent 3px 20px));
            opacity: 0.4;
        }

        .hero-simplify::after {
            background: radial-gradient(90% 115% at 50% -25%, rgba(255,255,255,0.82), transparent 62%);
        }

        .hero-simplify > * {
            position: relative;
            z-index: 1; /* keep hero content above decorative overlays for readable contrast */
        }

        .hero-badge {
            position: relative;
            overflow: hidden;
            animation: heroBadgeFloat 6s ease-in-out infinite alternate;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.3rem;
            border-radius: 999px;
            background: rgba(191,219,254,0.28);
            border: 1px solid rgba(148,197,255,0.45);
            color: rgba(30,58,138,0.8);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            box-shadow: 0 16px 36px -28px rgba(37,99,235,0.3);
        }
        .hero-badge-icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .hero-title {
            font-size: clamp(2.45rem, 4.5vw + 1rem, 4rem);
            font-weight: 700;
            line-height: 1.06;
            color: var(--hero-heading-color, #17223a);
            margin-bottom: 1.2rem;
            letter-spacing: -0.015em;
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-title-highlight {
            position: relative;
            display: inline-block;
            margin-top: 0.1rem;
        }

        .hero-title-highlight::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -0.5rem;
            height: 9px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(14,165,233,0.85), rgba(56,189,248,0.65));
        }

        .hero-copy {
            color: var(--hero-subtitle-color, rgba(71,85,105,0.78));
            max-width: 36rem;
            font-size: clamp(1.02rem, 2vw, 1.18rem);
            line-height: 1.7;
            margin: 0 auto;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.9rem;
            margin-top: 2.1rem;
        }

        .hero-actions .hero-btn-primary {
            min-width: clamp(180px, 26vw, 220px);
            background: var(--hero-primary-bg, linear-gradient(120deg, var(--accent) 0%, var(--accent-strong) 100%));
            color: var(--hero-primary-color, #fff) !important;
            border-radius: 999px;
            padding: 0.95rem 2.8rem;
            font-weight: 600;
            box-shadow: 0 22px 44px -24px rgba(37,99,235,0.38);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hero-actions .hero-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 26px 46px -24px rgba(37,99,235,0.45);
        }

        .hero-actions .hero-btn-secondary {
            border-radius: 999px;
            padding: 0.9rem 2.7rem;
            border: 1px solid var(--hero-secondary-border, rgba(148,163,184,0.3));
            color: var(--hero-secondary-color, #2563eb);
            font-weight: 600;
            background: rgba(255,255,255,0.96);
            box-shadow: 0 20px 44px -32px rgba(15,23,42,0.18);
            transition: all 0.2s ease;
        }

        .hero-actions .hero-btn-secondary:hover {
            background: rgba(236,245,255,0.92);
            border-color: rgba(59,130,246,0.35);
            color: rgba(29,78,216,0.95);
            box-shadow: 0 24px 46px -26px rgba(15,23,42,0.2);
        }

        .hero-rating {
            margin: 2.2rem auto 0;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            background: rgba(255,255,255,0.97);
            border: 1px solid rgba(148,197,255,0.32);
            box-shadow: 0 24px 48px -34px rgba(15,23,42,0.2);
            backdrop-filter: blur(12px);
            animation: heroFadeIn 1.6s ease both;
        }

        .hero-rating-stars {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .hero-rating-stars i {
            color: #facc15;
            font-size: 1.1rem;
        }

        .hero-rating-copy {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .hero-rating-text {
            font-weight: 600;
            color: var(--hero-heading-color, #0f172a);
        }

        .hero-rating-copy small {
            color: rgba(100,116,139,0.7);
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-pill-bar {
            margin: 2.4rem auto 0;
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.85rem 1.25rem;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(238,248,255,0.92) 100%);
            border: 1px solid rgba(148,197,255,0.26);
            box-shadow: 0 20px 44px -32px rgba(15,23,42,0.16);
            backdrop-filter: blur(16px);
            animation: heroFadeIn 1.8s ease both;
            align-self: center;
        }

        .hero-pill-bar .hero-pill {
            padding: 0.5rem 1.35rem 0.5rem 0.7rem;
            border-radius: 999px;
            background: var(--hero-pill-background, rgba(255,255,255,0.98));
            border: 1px solid var(--hero-pill-border, rgba(148,163,184,0.18));
            color: var(--text-primary, rgba(30,41,59,0.9));
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.58rem;
            box-shadow: 0 22px 36px -28px rgba(37,99,235,0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .hero-pill-icon {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: var(--hero-pill-icon-bg, rgba(37,99,235,0.12));
            color: rgba(37,99,235,0.88);
            box-shadow: inset 0 0 0 1px var(--hero-pill-icon-border, rgba(37,99,235,0.18));
        }

        .hero-pill-icon i {
            font-size: 1rem;
            line-height: 1;
        }

        .hero-pill-label {
            display: inline-block;
            line-height: 1.2;
            color: inherit;
        }

        .hero-pill-bar .hero-pill:hover {
            transform: translateY(-2px);
            background: rgba(229,244,255,0.75);
            border-color: rgba(59,130,246,0.35);
            color: #2563eb;
            box-shadow: 0 26px 40px -30px rgba(37,99,235,0.22);
        }

        .hero-logo-bar {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.9rem;
            padding: clamp(1.4rem, 3vw, 2.2rem) clamp(1.2rem, 4vw, 2.4rem);
            border-radius: 32px;
            background:
                linear-gradient(140deg, rgba(236,248,255,0.95), rgba(248,251,255,0.92)),
                repeating-linear-gradient(120deg, rgba(191,219,254,0.14) 0 2px, transparent 2px 18px);
            border: 1px solid rgba(148,197,255,0.24);
            box-shadow: 0 24px 60px -40px rgba(15,23,42,0.2);
            backdrop-filter: blur(14px);
            position: relative;
            overflow: hidden;
        }

        .hero-logo-bar::before {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(148,163,184,0.06) 0, rgba(148,163,184,0.06) 5px, transparent 5px, transparent 22px);
            opacity: 0.32;
            pointer-events: none;
        }

        .hero-logo-bar::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(75% 115% at 50% -20%, rgba(255,255,255,0.82), transparent 65%);
            pointer-events: none;
        }

        .hero-logo-bar > * {
            position: relative;
            z-index: 1;
        }

        .hero-logo-bar .text-muted {
            letter-spacing: 0.32em;
            font-weight: 700;
            color: var(--hero-channels-title-color, rgba(100,116,139,0.7)) !important;
            text-transform: uppercase;
        }

        .hero-logo-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: clamp(0.8rem, 2.5vw, 1.25rem);
            max-width: 760px;
        }

        .hero-logo-pill {
            padding: 0.55rem 1.35rem;
            border-radius: 999px;
            border: 1px solid var(--hero-channels-badge-border, rgba(148,197,255,0.28));
            background: var(--hero-channels-badge-background, linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(236,248,255,0.95) 100%));
            color: var(--hero-channels-badge-color, rgba(51,65,85,0.9));
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            box-shadow: 0 20px 44px -34px rgba(15,23,42,0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            white-space: nowrap;
            backdrop-filter: blur(6px);
        }

        .hero-logo-pill i {
            font-size: 1.1rem;
        }

        .hero-logo-pill:hover {
            transform: translateY(-2px);
            border-color: rgba(59,130,246,0.35);
            box-shadow: 0 26px 48px -30px rgba(37,99,235,0.25);
            color: rgba(37,99,235,0.95);
        }

        .hero-logo-pill:hover i {
            color: var(--accent-strong);
        }

        .hero-stats {
            margin-top: 2.4rem;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: clamp(1rem, 3vw, 1.6rem);
        }

        .hero-stat-card {
            background: var(--hero-stats-background, linear-gradient(150deg, rgba(255,255,255,0.98), rgba(237,247,255,0.94)));
            border: 1px solid var(--hero-stats-border, rgba(148,163,184,0.14));
            border-radius: 22px;
            padding: 1.2rem 1.4rem;
            box-shadow: 0 16px 32px -26px rgba(15,23,42,0.16);
            backdrop-filter: blur(10px);
            text-align: left;
        }

        .hero-stat-value {
            display: block;
            font-size: clamp(1.6rem, 3vw, 2.1rem);
            font-weight: 700;
            color: var(--hero-stats-value-color, #0f172a);
        }

        .hero-stat-label {
            display: block;
            margin-top: 0.45rem;
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--hero-stats-label-color, rgba(100,116,139,0.64));
        }

        .hero-workflow {
            width: 100%;
            margin-top: clamp(2rem, 5vw, 3rem);
        }

        .hero-workflow-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
            gap: clamp(1.5rem, 4vw, 2.2rem);
            align-items: stretch;
        }

        .hero-timeline-card {
            background: rgba(255,255,255,0.98);
            border-radius: 26px;
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 34px 70px -45px rgba(15,23,42,0.22);
            padding: clamp(1.6rem, 4vw, 2.2rem);
            text-align: left;
            backdrop-filter: blur(12px);
        }

        .hero-timeline-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 700;
            font-size: 0.74rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .hero-timeline-title {
            margin-top: 1.6rem;
            margin-bottom: 0.5rem;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .hero-timeline-copy {
            margin-bottom: 1.4rem;
            color: rgba(71,85,105,0.82);
        }

        .hero-timeline-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .hero-timeline-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: start;
            background: rgba(255,255,255,0.96);
            border-radius: 20px;
            border: 1px solid rgba(148,163,184,0.12);
            padding: 1rem 1.3rem;
            box-shadow: 0 22px 44px -34px rgba(15,23,42,0.18);
        }

        .hero-timeline-index {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 700;
            display: grid;
            place-items: center;
            font-size: 0.92rem;
        }

        .hero-timeline-step {
            margin: 0;
            font-weight: 600;
            color: var(--text-primary);
        }

        .hero-timeline-meta {
            margin: 0.3rem 0 0;
            color: rgba(71,85,105,0.78);
        }

        .hero-timeline-status {
            margin-top: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(14,165,233,0.85);
        }

        .hero-side-stack {
            display: flex;
            flex-direction: column;
            gap: clamp(1.2rem, 3vw, 1.6rem);
        }

        .hero-next-chat,
        .hero-confidence {
            background: rgba(255,255,255,0.96);
            border-radius: 26px;
            border: 1px solid rgba(148,163,184,0.12);
            padding: clamp(1.4rem, 4vw, 1.9rem);
            box-shadow: 0 24px 48px -36px rgba(15,23,42,0.18);
            text-align: left;
            backdrop-filter: blur(8px);
        }

        .hero-next-chat-head {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(71,85,105,0.7);
        }

        .hero-next-chat-head .mdi {
            font-size: 1.1rem;
            color: rgba(37,99,235,0.9);
        }

        .hero-next-chat-title {
            margin-top: 1rem;
            margin-bottom: 0.3rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .hero-next-chat-time {
            margin: 0;
            font-weight: 500;
            color: rgba(14,165,233,0.85);
        }

        .hero-next-chat-notes {
            margin-top: 0.6rem;
            margin-bottom: 0.8rem;
            color: rgba(71,85,105,0.78);
        }

        .hero-next-chat-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 600;
            color: var(--accent-strong);
            text-decoration: none;
        }

        .hero-next-chat-cta .mdi {
            font-size: 1rem;
        }

        .hero-confidence-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            background: rgba(226,232,240,0.55);
            color: rgba(51,65,85,0.85);
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .hero-confidence-score {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 700;
            margin-top: 1rem;
            color: var(--text-primary);
        }

        .hero-confidence-status {
            margin: 0.4rem 0 0;
            font-weight: 600;
            color: rgba(37,99,235,0.85);
        }

        .hero-confidence-footnote {
            margin-top: 0.75rem;
            margin-bottom: 0;
            color: rgba(71,85,105,0.72);
            font-size: 0.88rem;
        }

        .feature-card {
            position: relative;
            overflow: hidden;
            background: var(--feature-card-background, linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(239,247,255,0.93) 100%));
            border: 1px solid var(--feature-card-border, rgba(148,163,184,0.16));
            border-radius: 28px;
            padding: clamp(1.9rem, 4vw, 2.4rem);
            box-shadow: 0 28px 60px -48px rgba(15,23,42,0.18);
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: left;
            backdrop-filter: blur(12px);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .feature-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(120% 150% at 0% -20%, rgba(148,197,255,0.18), transparent 60%),
                radial-gradient(120% 160% at 100% 0%, rgba(191,219,254,0.15), transparent 65%);
            opacity: 0.45;
            pointer-events: none;
        }

        .feature-card > * {
            position: relative;
            z-index: 1;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 36px 72px -44px rgba(37,99,235,0.22);
            border-color: rgba(59,130,246,0.28);
        }

        .feature-card h3 {
            color: var(--feature-title-color, var(--text-primary));
        }

        .feature-card .text-subtle {
            color: var(--feature-description-color, rgba(71,85,105,0.85));
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--feature-icon-background, rgba(37,99,235,0.12));
            color: var(--feature-icon-color, rgba(37,99,235,0.78));
            box-shadow: inset 0 0 0 1px var(--feature-icon-border, rgba(59,130,246,0.18));
        }

        .feature-card .site-link {
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            color: var(--feature-link-color, #1d4ed8);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .feature-card .site-link .mdi {
            font-size: 1rem;
        }

        .feature-card .site-link:hover {
            color: var(--accent-strong, #2563eb);
        }

        .network-section {
            position: relative;
            background: linear-gradient(180deg, rgba(255,255,255,0.97) 0%, rgba(240,247,255,0.94) 100%);
            border-radius: 40px;
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 48px 90px -60px rgba(15,23,42,0.25);
            overflow: hidden;
            padding: clamp(3rem, 6vw, 4rem);
        }

        .network-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(70% 120% at 12% -10%, rgba(148,197,255,0.18), transparent 70%),
                radial-gradient(85% 130% at 92% -15%, rgba(191,219,254,0.16), transparent 75%),
                repeating-linear-gradient(120deg, rgba(148,163,184,0.05) 0, rgba(148,163,184,0.05) 4px, transparent 4px, transparent 22px);
            opacity: 0.55;
            pointer-events: none;
        }

        .network-section::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(120% 140% at 50% -25%, rgba(255,255,255,0.85), transparent 62%);
            pointer-events: none;
        }

        .network-section > * {
            position: relative;
            z-index: 1;
        }

        .network-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: clamp(2.2rem, 5vw, 3.5rem);
            align-items: start;
            padding: clamp(1rem, 4vw, 2.2rem);
            border-radius: 28px;
            background: rgba(255,255,255,0.6);
            box-shadow: inset 0 0 0 1px rgba(148,163,184,0.08);
            backdrop-filter: blur(18px);
        }

        .network-left,
        .network-right {
            position: relative;
            padding: clamp(0.4rem, 2vw, 1.1rem);
        }

        .network-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1.05rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 700;
            font-size: 0.74rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
        }

        .network-title {
            margin-top: 1.8rem;
            margin-bottom: 1.2rem;
        }

        .network-copy {
            max-width: 36rem;
            color: rgba(71,85,105,0.88);
            font-size: 1.02rem;
        }

        .network-steps {
            list-style: none;
            margin: clamp(1.8rem, 4vw, 2.4rem) 0 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(1.1rem, 3vw, 1.6rem);
        }

        .network-step {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.1rem 1.3rem;
            border-radius: 24px;
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(148,163,184,0.14);
            box-shadow: 0 26px 60px -48px rgba(15,23,42,0.18);
            backdrop-filter: blur(8px);
        }

        .network-step-body {
            flex: 1;
        }

        .network-step-index {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: rgba(14,165,233,0.14);
            box-shadow: inset 0 0 0 1px rgba(14,165,233,0.26);
            display: grid;
            place-items: center;
            font-weight: 700;
            color: rgba(14,165,233,0.9);
            font-size: 0.95rem;
        }

        .network-step-title {
            margin-bottom: 0.3rem;
            font-size: 1.02rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .network-step-copy {
            margin: 0;
            color: rgba(71,85,105,0.85);
            line-height: 1.6;
        }

        .network-stack {
            display: flex;
            flex-direction: column;
            gap: clamp(1.6rem, 4vw, 2.1rem);
        }

        .network-pane {
            background: rgba(255,255,255,0.95);
            border-radius: 28px;
            border: 1px solid rgba(148,163,184,0.16);
            padding: clamp(1.8rem, 4vw, 2.3rem);
            box-shadow: 0 32px 70px -50px rgba(15,23,42,0.22);
            backdrop-filter: blur(10px);
        }

        .network-pane--story {
            background: var(--ritual-testimonial-background, linear-gradient(135deg, rgba(244,248,255,0.9), rgba(224,237,255,0.75)));
        }

        .network-pane-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: rgba(148,163,184,0.18);
            color: rgba(71,85,105,0.85);
            font-size: 0.7rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .network-pane-title {
            margin-top: 1.4rem;
            margin-bottom: 0.8rem;
            font-size: 1.32rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .network-pane-copy {
            margin-bottom: 1.2rem;
            color: rgba(71,85,105,0.85);
        }

        .network-quote {
            margin: 0 0 1.6rem;
            font-size: 1.1rem;
            font-weight: 500;
            color: rgba(30,41,59,0.95);
            line-height: 1.6;
        }

        .network-quote-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .network-author-name {
            margin-bottom: 0.2rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .network-author-role {
            margin: 0;
            color: rgba(100,116,139,0.8);
            font-size: 0.9rem;
        }

        .network-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .network-badge--soft {
            background: rgba(59,130,246,0.16);
            color: rgba(37,99,235,0.95);
        }

        .network-badge--muted {
            background: rgba(100,116,139,0.18);
            color: rgba(51,65,85,0.85);
        }

        .network-pane-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.4rem;
        }

        .network-pane-heading {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .network-metric {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 1.2rem;
        }

        .network-metric-value {
            font-size: 2.4rem;
            font-weight: 700;
            color: rgba(30,41,59,0.98);
        }

        .network-metric-caption {
            font-size: 0.98rem;
            color: rgba(71,85,105,0.85);
        }

        .network-progress {
            position: relative;
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: rgba(191,219,254,0.4);
            overflow: hidden;
        }

        .network-progress-bar {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, rgba(14,165,233,0.95), rgba(2,132,199,0.95));
            box-shadow: 0 14px 24px -18px rgba(2,132,199,0.6);
        }

        .network-footnote {
            margin-top: 1.1rem;
            margin-bottom: 0;
            font-size: 0.88rem;
            color: rgba(100,116,139,0.85);
        }

        .home-mba-strip {
            position: relative;
            background: linear-gradient(180deg, rgba(244,251,255,0.95) 0%, rgba(233,244,255,0.88) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 32px;
            box-shadow: 0 40px 90px -60px rgba(15,23,42,0.24);
            overflow: hidden;
        }

        .home-mba-strip::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 22px);
            opacity: 0.45;
            pointer-events: none;
        }

        .home-mba-strip > * {
            position: relative;
            z-index: 1;
        }

        .home-mba-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-left: clamp(1.2rem, 4vw, 2.6rem);
            padding-right: clamp(1.2rem, 4vw, 2.6rem);
        }

        .home-mba-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 600;
            letter-spacing: 0.22em;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .home-mba-badge .mdi {
            font-size: 1rem;
        }

        .home-mba-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: clamp(1.4rem, 3vw, 2.1rem);
            margin-top: clamp(1.6rem, 4vw, 2.2rem);
            padding-left: clamp(1.2rem, 4vw, 2.6rem);
            padding-right: clamp(1.2rem, 4vw, 2.6rem);
            padding-bottom: clamp(1.4rem, 4vw, 2.4rem);
        }

        .home-mba-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 24px;
            padding: clamp(1.4rem, 4vw, 1.8rem);
            box-shadow: 0 30px 64px -48px rgba(15,23,42,0.18);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .home-mba-card header {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .home-mba-card img {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(148,163,184,0.18);
            background: #fff;
            object-fit: cover;
        }

        .home-mba-card h3 {
            font-size: 1.08rem;
            margin-bottom: 0.2rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .home-mba-card .location {
            font-size: 0.9rem;
            color: rgba(71,85,105,0.78);
        }

        .home-mba-card .description {
            color: rgba(71,85,105,0.85);
            line-height: 1.55;
            flex-grow: 1;
        }

        .home-mba-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .home-mba-footer .count {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: rgba(71,85,105,0.75);
        }

        .home-mba-footer .action-group {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            align-items: center;
        }

        .home-mba-footer .follow-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.55rem 1.3rem;
            font-weight: 600;
            background: rgba(37,99,235,0.12);
            border: 1px solid rgba(37,99,235,0.26);
            color: rgba(37,99,235,0.92);
            transition: all 0.2s ease;
        }

        .home-mba-footer .follow-btn:hover {
            background: rgba(37,99,235,0.18);
            color: var(--accent-strong);
        }

        .cta-scale {
            position: relative;
            overflow: hidden;
            border-radius: 40px;
            padding: var(--cta-padding, clamp(3rem, 6vw, 4rem) clamp(2rem, 6vw, 4.5rem));
            background: var(--cta-background,
                radial-gradient(120% 140% at 20% -40%, rgba(56,189,248,0.32), transparent 65%),
                radial-gradient(120% 140% at 90% -30%, rgba(14,165,233,0.24), transparent 70%),
                linear-gradient(135deg, rgba(15,23,42,0.02) 0%, rgba(244,251,255,0.88) 100%)
            );
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 52px 120px -68px rgba(15,23,42,0.32);
        }

        .cta-scale::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--cta-overlay, repeating-linear-gradient(115deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 18px));
            opacity: 0.45;
            pointer-events: none;
        }

        .cta-scale::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(120% 160% at 50% -30%, rgba(255,255,255,0.82), transparent 60%);
            pointer-events: none;
        }

        .cta-scale > * {
            position: relative;
            z-index: 1;
        }

        .cta-scale-inner {
            width: 100%;
            padding: 0 clamp(1.8rem, 5vw, 3rem);
        }

        .cta-scale h2 {
            margin-bottom: 1.4rem !important;
            max-width: 34rem;
        }

        .cta-scale .cta-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            align-items: center;
            justify-content: center;
        }

        .cta-scale .cta-actions .btn {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.9rem 2.4rem;
            box-shadow: 0 30px 60px -40px rgba(15,23,42,0.28);
        }

        .cta-scale .cta-actions .btn-dark {
            background: linear-gradient(120deg, rgba(15,23,42,0.95), rgba(30,41,59,0.88));
            border: none;
        }

        .cta-scale .cta-actions .btn-dark:hover {
            box-shadow: 0 34px 70px -44px rgba(15,23,42,0.32);
        }

        .cta-scale .cta-actions .btn-outline-light {
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(148,163,184,0.2);
            color: var(--text-primary);
        }

        .cta-scale .cta-actions .btn-outline-light:hover {
            border-color: rgba(14,165,233,0.35);
            color: var(--accent-strong);
            box-shadow: 0 34px 70px -44px rgba(14,165,233,0.3);
        }

        .hero-top {
            max-width: 760px;
            margin: 0 auto;
            width: 100%;
        }

        @media (max-width: 768px) {
            .hero-simplify {
                border-radius: 40px;
                padding: clamp(3.6rem, 9vw, 4.8rem) clamp(1.6rem, 6vw, 2.4rem);
            }
            .hero-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
            .hero-actions .btn {
                display: inline-flex;
                align-items: center;
                width: 100%;
                justify-content: center;
            }
            .hero-stats {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            .hero-stat-card {
                text-align: center;
                padding: 1.1rem 1.2rem;
            }
            .hero-workflow-grid {
                grid-template-columns: minmax(0, 1fr);
            }
            .hero-side-stack {
                flex-direction: column;
            }
            .hero-pill-bar {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
            }
            .hero-pill-bar .hero-pill {
                width: 100%;
                display: flex;
                justify-content: flex-start;
            }
            .hero-logo-bar {
                padding: clamp(1.1rem, 5vw, 1.6rem);
                border-radius: 26px;
            }
            .hero-logo-list {
                justify-content: flex-start;
                gap: 0.6rem;
            }
            .hero-logo-pill {
                width: 100%;
                justify-content: flex-start;
            }
            .hero-timeline-card {
                padding: clamp(1.2rem, 6vw, 1.6rem);
                text-align: center;
            }
            .hero-timeline-badge {
                margin: 0 auto;
            }
            .hero-timeline-title {
                font-size: 1.28rem;
            }
            .hero-timeline-meta {
                text-align: center;
            }
            .hero-next-chat,
            .hero-confidence {
                padding: clamp(1.2rem, 6vw, 1.6rem);
                text-align: center;
            }
            .hero-next-chat-head {
                justify-content: center;
            }
            .hero-next-chat-cta {
                width: 100%;
                justify-content: center;
            }
            .hero-confidence-footnote {
                text-align: center;
            }
            .network-section {
                padding: clamp(2.2rem, 7vw, 3rem);
                border-radius: 32px;
            }
            .network-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 1.6rem;
                padding: clamp(0.75rem, 4vw, 1.4rem);
            }
            .network-left,
            .network-right {
                padding: 0;
            }
            .network-steps {
                gap: 1rem;
            }
            .network-step {
                padding: 1rem 1.1rem;
            }
            .network-pane {
                padding: clamp(1.1rem, 6vw, 1.5rem);
                text-align: center;
            }
            .network-pane-head {
                flex-direction: column;
                align-items: center;
                gap: 0.6rem;
            }
            .network-quote {
                font-size: 1.05rem;
            }
            .network-metric {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.4rem;
            }
            .home-mba-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1.25rem;
                padding-left: clamp(1rem, 6vw, 1.6rem);
                padding-right: clamp(1rem, 6vw, 1.6rem);
            }
            .home-mba-header .btn {
                width: 100%;
                justify-content: center;
            }
            .home-mba-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 1.25rem;
                padding-left: clamp(0.9rem, 5vw, 1.6rem);
                padding-right: clamp(0.9rem, 5vw, 1.6rem);
            }
            .home-mba-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.9rem;
            }
            .home-mba-footer .action-group {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 0.7rem;
            }
            .home-mba-footer .action-group > * {
                display: inline-flex;
                align-items: center;
                width: 100%;
                justify-content: center;
                text-align: center;
            }
            .cta-scale {
                border-radius: 32px;
                padding: clamp(2.4rem, 7vw, 3.2rem) clamp(1.4rem, 6vw, 2.4rem);
            }
            .cta-scale-inner {
                padding: 0;
                text-align: center;
            }
            .cta-scale .cta-actions {
                width: 100%;
            }
            .cta-scale .cta-actions .btn {
                display: inline-flex;
                align-items: center;
                flex: 1 1 100%;
                justify-content: center;
            }
            .feature-card {
                padding: clamp(1.5rem, 6vw, 1.9rem);
                gap: 0.85rem;
                text-align: center;
            }
            .feature-card .site-link {
                justify-content: center;
            }
        }
    </style>

    <section class="hero-simplify mb-5" @if($heroSectionStyle) style="{{ $heroSectionStyle }}" @endif>
        <div class="hero-top text-center">
            @if($heroBadge)
                <span class="hero-badge">
                    <img src="{{ asset('coffeechat-os-favicon.png?v=3') }}" alt="CoffeeChat OS" class="hero-badge-icon">
                    {{ $heroBadge }}
                </span>
            @endif
            <h1 class="hero-title mt-4">{{ $hero->title ?? 'Orchestrate world-class coffee chats with AI precision, powered by warm introductions in one workspace.' }}</h1>
            <p class="hero-copy mb-4">{{ $hero->subtitle ?? 'Power every informational interview with structured prep, live intelligence, and automated follow-through. CoffeeChat OS is the scale engine for relationship builders.' }}</p>
            <div class="hero-actions">
                <a href="{{ url(data_get($heroPrimaryButton, 'url', '/register')) }}" class="btn hero-btn-primary">
                    @if($icon = data_get($heroPrimaryButton, 'icon'))
                        <i class="mdi {{ $icon }} me-2"></i>
                    @endif
                    {{ data_get($heroPrimaryButton, 'label', "Sign up - it's free!") }}
                </a>
                <a href="{{ url(data_get($heroSecondaryButton, 'url', '/stories')) }}" class="btn hero-btn-secondary">
                    {{ data_get($heroSecondaryButton, 'label', 'Explore platform') }}
                </a>
            </div>
            @if($heroRatingText)
                <div class="hero-rating">
                    <span class="hero-rating-stars">
                        @for($i = 0; $i < max($heroRatingStars, 1); $i++)
                            <i class="mdi mdi-star"></i>
                        @endfor
                    </span>
                    <div class="hero-rating-copy">
                        <span class="hero-rating-text">{{ $heroRatingText }}</span>
                        @if($heroRatingCaption)
                            <small>{{ $heroRatingCaption }}</small>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if($heroPillItems->isNotEmpty())
            <div class="hero-pill-bar">
                @foreach($heroPillItems as $pill)
                    <span class="hero-pill">
                        @if($icon = data_get($pill, 'icon'))
                            <span class="hero-pill-icon">
                                <i class="mdi {{ $icon }}"></i>
                            </span>
                        @endif
                        <span class="hero-pill-label">{{ data_get($pill, 'label') }}</span>
                    </span>
                @endforeach
            </div>
        @endif

        @if($heroLogos->isNotEmpty())
            <div class="hero-logo-bar mt-5">
                <span class="text-muted text-uppercase small fw-semibold">Works across</span>
                <div class="hero-logo-list">
                    @foreach($heroLogos as $channel)
                        <span class="hero-logo-pill">
                            @if($icon = data_get($channel, 'icon'))
                                <i class="mdi {{ $icon }}"></i>
                            @endif
                            {{ data_get($channel, 'label') }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($heroStats->isNotEmpty())
            <div class="hero-stats">
                @foreach($heroStats as $stat)
                    <div class="hero-stat-card">
                        <span class="hero-stat-value">{{ data_get($stat, 'value') }}</span>
                        <span class="hero-stat-label">{{ data_get($stat, 'label') }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($heroHasWorkflow)
            <div class="hero-workflow">
                <div class="hero-workflow-grid">
                    @if($heroTimelineItems->isNotEmpty())
                        <div class="hero-timeline-card">
                            <span class="hero-timeline-badge">{{ $heroTimelineBadge }}</span>
                            <h3 class="hero-timeline-title">{{ $heroTimelineTitle }}</h3>
                            @if($heroTimelineDescription)
                                <p class="hero-timeline-copy">{{ $heroTimelineDescription }}</p>
                            @endif
                            <ul class="hero-timeline-list">
                                @foreach($heroTimelineItems as $item)
                                    <li class="hero-timeline-item">
                                        <span class="hero-timeline-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div class="hero-timeline-body">
                                            <p class="hero-timeline-step">{{ data_get($item, 'title', data_get($item, 'label')) }}</p>
                                            @if($description = data_get($item, 'description'))
                                                <p class="hero-timeline-meta">{{ $description }}</p>
                                            @endif
                                            @if($status = data_get($item, 'status'))
                                                <span class="hero-timeline-status">
                                                    <span class="mdi mdi-circle-medium"></span>{{ $status }}
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="hero-side-stack">
                        @if($showNextChatCard)
                            <div class="hero-next-chat">
                                <div class="hero-next-chat-head">
                                    <span class="mdi mdi-calendar-clock"></span>
                                    <span class="hero-next-chat-label">{{ $nextChat->get('label', 'Next coffee chat') }}</span>
                                </div>
                                @if($title = $nextChat->get('title'))
                                    <h4 class="hero-next-chat-title">{{ $title }}</h4>
                                @endif
                                @if($schedule = $nextChat->get('schedule'))
                                    <p class="hero-next-chat-time">{{ $schedule }}</p>
                                @endif
                                @if($notes = $nextChat->get('notes'))
                                    <p class="hero-next-chat-notes">{{ $notes }}</p>
                                @endif
                                @if($link = $nextChat->get('link'))
                                    <a href="{{ url($link) }}" class="hero-next-chat-cta">
                                        {{ $nextChat->get('cta', 'Open workspace') }}
                                        <span class="mdi mdi-arrow-top-right"></span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($showConfidenceCard)
                            <div class="hero-confidence">
                                <span class="hero-confidence-badge">{{ $heroConfidence->get('title', 'Confidence pulse') }}</span>
                                @if($score = $heroConfidence->get('score'))
                                    <div class="hero-confidence-score">{{ $score }}</div>
                                @endif
                                @if($status = $heroConfidence->get('status'))
                                    <p class="hero-confidence-status">{{ $status }}</p>
                                @endif
                                @if($caption = $heroConfidence->get('caption'))
                                    <p class="hero-confidence-footnote">{{ $caption }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </section>

    <section class="site-section" @if($featuresSectionStyle) style="{{ $featuresSectionStyle }}" @endif>
        <header class="site-section-header text-center mb-4">
            @if($featureEyebrow)
                <p class="site-eyebrow mb-2">{{ $featureEyebrow }}</p>
            @endif
            <h2 class="display-6 fw-semibold">{{ $featureComponent->title ?? 'CoffeeChat OS platform pillars' }}</h2>
            @if(filled($featureSubtitle))
                <p class="text-subtle lead mx-auto" style="max-width:36rem;">{{ $featureSubtitle }}</p>
            @endif
            @if($featureLink)
                <a href="{{ url(data_get($featureLink, 'url', '/stories')) }}" class="site-link mt-2 d-inline-flex align-items-center gap-1">
                    {{ data_get($featureLink, 'label', 'See customer stories') }} <span class="mdi mdi-arrow-right"></span>
                </a>
            @endif
        </header>
        <div class="row g-4">
            @foreach($featureItems as $feature)
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        @if($icon = data_get($feature, 'icon'))
                            <div class="feature-icon mb-3">
                                <span class="mdi {{ $icon }} fs-5"></span>
                            </div>
                        @endif
                        <h3 class="h4 fw-semibold mb-3">{{ data_get($feature, 'title') }}</h3>
                        <p class="text-subtle mb-4">{{ data_get($feature, 'description') }}</p>
                        @if(data_get($feature, 'link_url') && data_get($feature, 'link_text'))
                            <a href="{{ url(data_get($feature, 'link_url')) }}" class="site-link">{{ data_get($feature, 'link_text') }}</a>
                        @elseif($footnote = data_get($feature, 'footnote'))
                            <p class="text-subtle small mb-0">{{ $footnote }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="site-section network-section" @if($ritualSectionStyle) style="{{ $ritualSectionStyle }}" @endif>
        <div class="network-grid">
            <div class="network-left">
                <span class="network-eyebrow">{{ $ritualEyebrow }}</span>
                <h2 class="display-6 fw-semibold network-title">{{ $ritual->title ?? 'Turn open vacancies into warm conversations in four steps.' }}</h2>
                <p class="network-copy">{{ $ritual->subtitle ?? 'CoffeeChat OS links vacancy tracking, team discovery, and outreach so momentum compounds automatically.' }}</p>
                <ul class="network-steps">
                    @foreach($ritualSteps as $step)
                        <li class="network-step">
                            <span class="network-step-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="network-step-body">
                                <h5 class="network-step-title">{{ data_get($step, 'label') }}</h5>
                                <p class="network-step-copy">{{ data_get($step, 'description') }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="network-right">
                <div class="network-stack">
                    <div class="network-pane network-pane--story">
                        <span class="network-pane-eyebrow">{{ data_get($trusted, 'title', 'Trusted by') }}</span>
                        <h3 class="network-pane-title">{{ data_get($trusted, 'headline', 'Community builders') }}</h3>
                        <p class="network-pane-copy">{{ data_get($trusted, 'body', 'From MBA cohorts to engineering guilds, leaders rely on CoffeeChat OS to orchestrate programs that scale.') }}</p>
                        <blockquote class="network-quote">“{{ data_get($testimonial, 'quote', 'Share outcomes from your community by updating the testimonial component in the dashboard.') }}”</blockquote>
                        <div class="network-quote-footer">
                            <div>
                                <p class="network-author-name">{{ data_get($testimonial, 'author', 'Network leader') }}</p>
                                <p class="network-author-role">{{ data_get($testimonial, 'role', 'Role / Company') }}</p>
                            </div>
                            @if($badge = data_get($testimonial, 'badge'))
                                <span class="network-badge network-badge--soft">{{ $badge }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="network-pane">
                        <div class="network-pane-head">
                            <h5 class="network-pane-heading">{{ data_get($networkHealth, 'title', 'Network health index') }}</h5>
                            @if($tag = data_get($networkHealth, 'tag'))
                                <span class="network-badge network-badge--muted">{{ $tag }}</span>
                            @endif
                        </div>
                        <div class="network-metric">
                            <span class="network-metric-value">{{ data_get($networkHealth, 'value', '29 active') }}</span>
                            <span class="network-metric-caption">{{ data_get($networkHealth, 'description', 'relationships nurtured this quarter') }}</span>
                        </div>
                        <div class="network-progress">
                            <div class="network-progress-bar" style="width: {{ data_get($networkHealth, 'progress', 68) }}%;"></div>
                        </div>
                        <p class="network-footnote">{{ data_get($networkHealth, 'footnote', 'Keep your cadence steady by closing the follow-up loop faster than your peers.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $homeMbaJobs = ($mbaJobs ?? collect())->take(3);
        $homeMbaInternships = ($mbaInternships ?? collect())->take(3);
    @endphp

    @if($homeMbaJobs->isNotEmpty())
        <section class="site-section home-mba-strip mt-4">
            <div class="home-mba-header">
                <div>
                    <span class="home-mba-badge"><span class="mdi mdi-briefcase-outline"></span> MBA jobs</span>
                    <h2 class="h4 fw-semibold mt-3 mb-1">Latest full-time roles curated for MBA operators</h2>
                    <p class="text-subtle mb-0">Hand-picked roles with strategic scope, operating leverage, and direct application links.</p>
                </div>
                <a href="{{ route('mba.jobs') }}" class="btn btn-outline-primary">
                    View all roles <span class="mdi mdi-arrow-right"></span>
                </a>
            </div>
            <div class="home-mba-grid mt-4">
                @foreach($homeMbaJobs as $job)
                    @php
                        $jobCity = trim(\Illuminate\Support\Str::before(data_get($job, 'location', ''), '·'));
                        $jobFollowUrl = route('workspace.team-finder.index', array_filter([
                            'position' => data_get($job, 'title'),
                            'company' => data_get($job, 'company'),
                            'city' => $jobCity,
                        ], fn ($value) => filled($value)));
                    @endphp
                    <article class="home-mba-card">
                        <header>
                            @if(data_get($job, 'logo'))
                                <img src="{{ data_get($job, 'logo') }}" alt="{{ data_get($job, 'company') }} logo">
                            @else
                                <span class="mdi mdi-domain text-primary fs-4"></span>
                            @endif
                            <div>
                                <h3 class="mb-1">{{ data_get($job, 'title') }}</h3>
                                <span class="text-subtle small">{{ data_get($job, 'company') }}</span>
                            </div>
                        </header>
                        <span class="location text-subtle small">
                            <span class="mdi mdi-map-marker-outline"></span>
                            {{ data_get($job, 'location') }}
                        </span>
                        <p class="description mb-0">{{ \Illuminate\Support\Str::limit(data_get($job, 'description'), 140) }}</p>
                        <div class="home-mba-footer">
                            <span class="count">
                                <span class="mdi mdi-check-circle-outline"></span> Direct apply
                            </span>
                            <div class="action-group">
                                <a href="{{ $jobFollowUrl }}" class="follow-btn">
                                    <span class="mdi mdi-account-search-outline"></span>
                                    Follow role
                                </a>
                                <a href="{{ data_get($job, 'apply_url') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                    Apply
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($homeMbaInternships->isNotEmpty())
        <section class="site-section home-mba-strip mt-4">
            <div class="home-mba-header">
                <div>
                    <span class="home-mba-badge"><span class="mdi mdi-school-outline"></span> MBA internships</span>
                    <h2 class="h4 fw-semibold mt-3 mb-1">Summer internship opportunities worth tracking</h2>
                    <p class="text-subtle mb-0">Operator tracks, venture programs, and rotational roles designed for MBAs.</p>
                </div>
                <a href="{{ route('mba.jobs') }}#mba-internships" class="btn btn-outline-primary">
                    View all internships <span class="mdi mdi-arrow-right"></span>
                </a>
            </div>
            <div class="home-mba-grid mt-4">
                @foreach($homeMbaInternships as $job)
                    @php
                        $internCity = trim(\Illuminate\Support\Str::before(data_get($job, 'location', ''), '·'));
                        $internFollowUrl = route('workspace.team-finder.index', array_filter([
                            'position' => data_get($job, 'title'),
                            'company' => data_get($job, 'company'),
                            'city' => $internCity,
                        ], fn ($value) => filled($value)));
                    @endphp
                    <article class="home-mba-card">
                        <header>
                            @if(data_get($job, 'logo'))
                                <img src="{{ data_get($job, 'logo') }}" alt="{{ data_get($job, 'company') }} logo">
                            @else
                                <span class="mdi mdi-domain text-primary fs-4"></span>
                            @endif
                            <div>
                                <h3 class="mb-1">{{ data_get($job, 'title') }}</h3>
                                <span class="text-subtle small">{{ data_get($job, 'company') }}</span>
                            </div>
                        </header>
                        <span class="location text-subtle small">
                            <span class="mdi mdi-map-marker-outline"></span>
                            {{ data_get($job, 'location') }}
                        </span>
                        @if(data_get($job, 'duration'))
                            <span class="text-subtle small d-inline-flex align-items-center gap-1">
                                <span class="mdi mdi-clock-outline"></span>
                                {{ data_get($job, 'duration') }}
                            </span>
                        @endif
                        <p class="description mb-0">{{ \Illuminate\Support\Str::limit(data_get($job, 'description'), 140) }}</p>
                        <div class="home-mba-footer">
                            <span class="count">
                                <span class="mdi mdi-open-in-new"></span>
                                Direct apply
                            </span>
                            <div class="action-group">
                                <a href="{{ $internFollowUrl }}" class="follow-btn">
                                    <span class="mdi mdi-account-search-outline"></span>
                                    Follow role
                                </a>
                                <a href="{{ data_get($job, 'apply_url') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                    Apply
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="site-section site-section--transparent cta-scale mt-4" @if($ctaSectionStyle) style="{{ $ctaSectionStyle }}" @endif>
        <div class="cta-scale-inner">
            <div class="row g-5 align-items-center position-relative" style="z-index:2;">
                <div class="col-lg-7">
                    <span class="network-pane-eyebrow" style="background:rgba(14,165,233,0.15);color:rgba(14,165,233,0.85);">Scale programs</span>
                    <h2 class="display-6 fw-semibold mt-3 mb-3">{{ $cta->title ?? 'Deploy CoffeeChat OS across your organisation.' }}</h2>
                    <p class="fs-5 text-subtle mb-0" style="max-width:40rem;">{{ $cta->subtitle ?? 'Pair your team with our success architects to design workflows, analytics, and automations tailored to your relationship goals.' }}</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="cta-actions justify-content-lg-end justify-content-center">
                        <a href="{{ url(data_get($ctaPrimary, 'url', '/contact')) }}" class="btn btn-dark btn-lg">{{ data_get($ctaPrimary, 'label', 'Talk to sales') }}</a>
                        <a href="{{ url(data_get($ctaSecondary, 'url', '/register')) }}" class="btn btn-outline-light btn-lg">{{ data_get($ctaSecondary, 'label', 'Start free') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.site')

@section('content')
    @php
        $hero = $components['hero'] ?? null;
        $heroMeta = $hero?->meta ?? [];
        $heroBadge = data_get($heroMeta, 'badge');
        $heroStats = collect(data_get($heroMeta, 'stats', []));
        $heroTimeline = collect(data_get($heroMeta, 'timeline', []));
        $heroChannels = collect(data_get($heroMeta, 'channels', []));
        $heroConfidence = data_get($heroMeta, 'confidence', []);
        $heroPrimaryButton = data_get($heroMeta, 'primary_button', ['label' => 'Get started', 'url' => '/register']);
        $heroSecondaryButton = data_get($heroMeta, 'secondary_button', ['label' => 'Explore platform', 'url' => '/stories']);
        $nextChat = data_get($heroMeta, 'next_chat', [
            'title' => 'Coffee with Priya @ Stripe',
            'schedule' => 'Wed · 9:00 AM',
        ]);

        $featureComponent = $components['features'] ?? null;
        $featureItems = collect(data_get($featureComponent?->meta, 'features', []));

        $ritual = $components['ritual'] ?? null;
        $ritualMeta = $ritual?->meta ?? [];
        $ritualSteps = collect(data_get($ritualMeta, 'steps', []));
        $trusted = data_get($ritualMeta, 'trusted', []);
        $testimonial = data_get($ritualMeta, 'testimonial', []);
        $networkHealth = data_get($ritualMeta, 'network_health', []);

        $cta = $components['cta'] ?? null;
        $ctaMeta = $cta?->meta ?? [];
        $ctaPrimary = data_get($ctaMeta, 'primary_button', ['label' => 'Talk to us', 'url' => 'mailto:hello@coffeechat.os']);
        $ctaSecondary = data_get($ctaMeta, 'secondary_button', ['label' => 'Start for free', 'url' => '/register']);
    @endphp

    @php
        $heroStyle = data_get($hero?->style, 'hero', []);
        $featuresStyle = data_get($featureComponent?->style, 'features', []);
        $ritualStyle = data_get($ritual?->style, 'ritual', []);
        $ctaStyle = data_get($cta?->style, 'cta', []);
    @endphp

    @php
        $heroHighlight = data_get($heroMeta, 'highlight', 'Powered by warm introductions in one workspace.');
        $heroLogos = $heroChannels->take(8);
        $heroStatItems = $heroStats->isNotEmpty() ? $heroStats : collect([
            ['value' => '+2k', 'label' => 'Coffee chats logged'],
            ['value' => '74%', 'label' => 'Faster follow-up completion'],
        ]);
        $heroChecklistItems = $heroTimeline->isNotEmpty() ? $heroTimeline : collect([
            ['title' => 'Prep Deck', 'description' => 'Research stakeholders, latest moves, and key talking points.'],
            ['title' => 'Live Chat', 'description' => 'Guide the conversation with real-time prompts.'],
            ['title' => 'Follow-up', 'description' => 'Send notes, materials, and next steps automatically.'],
        ]);
        $heroRating = data_get($heroMeta, 'rating', []);
        $heroRatingStars = (int) data_get($heroRating, 'stars', 5);
        $heroRatingText = data_get($heroRating, 'text', 'Join 2,500+ connectors who trust CoffeeChat OS');
        $heroRatingCaption = data_get($heroRating, 'caption', 'Rated 5.0 by program leads.');
        $heroPillItems = collect(data_get($heroMeta, 'pills', []));
        if ($heroPillItems->isEmpty()) {
            $heroPillItems = collect([
                ['icon' => 'mdi-magnify', 'label' => 'Smart matches'],
                ['icon' => 'mdi-flash', 'label' => 'Coffee chat copilots'],
                ['icon' => 'mdi-file-document-edit-outline', 'label' => 'AI follow-ups'],
                ['icon' => 'mdi-chart-timeline-variant', 'label' => 'Pipeline tracker'],
            ]);
        }
    @endphp

    <style>
        .hero-simplify {
            position: relative;
            width: clamp(320px, 92vw, 1200px);
            border-radius: 52px;
            padding: clamp(4.6rem, 7vw, 6.6rem) clamp(2.6rem, 6vw, 5.4rem);
            background:
                radial-gradient(circle at 6% -10%, rgba(56,189,248,0.28), transparent 58%),
                radial-gradient(circle at 94% 4%, rgba(14,165,233,0.22), transparent 60%),
                linear-gradient(180deg, rgba(244,251,255,0.96) 0%, rgba(255,255,255,0.92) 58%, rgba(244,251,255,0.9) 100%);
            overflow: hidden;
            box-shadow: 0 55px 120px -60px rgba(15,23,42,0.24);
            border: 1px solid rgba(148,163,184,0.14);
            backdrop-filter: blur(26px);
            isolation: isolate;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .hero-simplify::before,
        .hero-simplify::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .hero-simplify::before {
            background: repeating-linear-gradient(110deg, rgba(14,165,233,0.08) 0, rgba(14,165,233,0.08) 3px, transparent 3px, transparent 18px);
            opacity: 0.55;
        }

        .hero-simplify::after {
            background: radial-gradient(84% 115% at 50% -22%, rgba(255,255,255,0.82), transparent 60%);
        }

        .hero-badge {
            position: relative;
            overflow: hidden;
            animation: heroBadgeFloat 6s ease-in-out infinite alternate;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: var(--accent-strong);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }

        .hero-title {
            font-size: clamp(2.45rem, 4.5vw + 1rem, 4rem);
            font-weight: 700;
            line-height: 1.06;
            color: var(--text-primary);
            margin-bottom: 1.2rem;
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
            color: rgba(71,85,105,0.9);
            max-width: 36rem;
            font-size: 1.08rem;
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
            background: linear-gradient(120deg, var(--accent) 0%, var(--accent-strong) 100%);
            color: #fff !important;
            border-radius: 999px;
            padding: 0.92rem 2.9rem;
            font-weight: 600;
            box-shadow: 0 28px 48px -30px rgba(2,132,199,0.65);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hero-actions .hero-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 26px 40px -25px rgba(2,132,199,0.85);
        }

        .hero-actions .hero-btn-secondary {
            border-radius: 999px;
            padding: 0.92rem 2.7rem;
            border: 1px solid rgba(14,165,233,0.25);
            color: var(--accent-strong);
            font-weight: 600;
            background: rgba(255,255,255,0.9);
            transition: all 0.2s ease;
        }

        .hero-actions .hero-btn-secondary:hover {
            background: rgba(14,165,233,0.12);
            border-color: rgba(14,165,233,0.4);
            color: var(--accent);
        }

        .hero-rating {
            margin: 2.6rem auto 0;
            display: inline-flex;
            align-items: center;
            gap: 1.1rem;
            flex-wrap: wrap;
            padding: 0.82rem 1.4rem;
            border-radius: 18px;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(14,165,233,0.16);
            backdrop-filter: blur(8px);
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
            color: rgba(15,23,42,0.9);
        }

        .hero-rating-copy small {
            color: rgba(100,116,139,0.75);
            font-weight: 500;
            letter-spacing: 0.045em;
            text-transform: uppercase;
        }

        .hero-pill-bar {
            margin: 2.6rem auto 0;
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.75rem 1.1rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.75);
            border: 1px solid rgba(15,23,42,0.08);
            box-shadow: 0 24px 42px -32px rgba(15,23,42,0.22);
            backdrop-filter: blur(14px);
            animation: heroFadeIn 1.8s ease both;
            align-self: center;
        }

        .hero-pill-bar .hero-pill {
            padding: 0.55rem 1.45rem 0.55rem 0.65rem;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(255,255,255,0.97) 0%, rgba(244,251,255,0.9) 100%);
            border: 1px solid rgba(14,165,233,0.16);
            color: rgba(30,41,59,0.95);
            font-size: 0.92rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            box-shadow: 0 24px 44px -32px rgba(15,23,42,0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .hero-pill-icon {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: rgba(14,165,233,0.16);
            color: var(--accent-strong);
            box-shadow: inset 0 0 0 1px rgba(14,165,233,0.26);
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
            background: rgba(14,165,233,0.12);
            border-color: rgba(14,165,233,0.35);
            color: var(--accent-strong);
            box-shadow: 0 28px 48px -30px rgba(15,23,42,0.24);
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
            background: linear-gradient(180deg, rgba(244,251,255,0.92) 0%, rgba(233,244,255,0.85) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 28px 70px -50px rgba(15,23,42,0.24);
            backdrop-filter: blur(18px);
            position: relative;
            overflow: hidden;
        }

        .hero-logo-bar::before {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(148,163,184,0.06) 0, rgba(148,163,184,0.06) 5px, transparent 5px, transparent 22px);
            opacity: 0.5;
            pointer-events: none;
        }

        .hero-logo-bar::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(80% 110% at 50% -20%, rgba(255,255,255,0.8), transparent 65%);
            pointer-events: none;
        }

        .hero-logo-bar > * {
            position: relative;
            z-index: 1;
        }

        .hero-logo-bar .text-muted {
            letter-spacing: 0.32em;
            font-weight: 700;
            color: rgba(100,116,139,0.7) !important;
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
            border: 1px solid rgba(148,163,184,0.2);
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.9) 100%);
            color: rgba(51,65,85,0.9);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            box-shadow: 0 16px 32px -24px rgba(15,23,42,0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            white-space: nowrap;
            backdrop-filter: blur(6px);
        }

        .hero-logo-pill i {
            font-size: 1.1rem;
        }

        .hero-logo-pill:hover {
            transform: translateY(-2px);
            border-color: rgba(14,165,233,0.35);
            box-shadow: 0 22px 42px -28px rgba(15,23,42,0.28);
            color: var(--accent-strong);
        }

        .hero-logo-pill:hover i {
            color: var(--accent-strong);
        }

        .network-section {
            position: relative;
            background: linear-gradient(180deg, rgba(244,251,255,0.9) 0%, rgba(255,255,255,0.85) 100%);
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
                radial-gradient(70% 120% at 12% -10%, rgba(59,130,246,0.18), transparent 70%),
                radial-gradient(85% 130% at 92% -15%, rgba(56,189,248,0.14), transparent 75%),
                repeating-linear-gradient(120deg, rgba(148,163,184,0.06) 0, rgba(148,163,184,0.06) 4px, transparent 4px, transparent 22px);
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
            background: rgba(255,255,255,0.42);
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
            background: rgba(255,255,255,0.72);
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 26px 60px -48px rgba(15,23,42,0.3);
            backdrop-filter: blur(10px);
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
            background: rgba(255,255,255,0.78);
            border-radius: 28px;
            border: 1px solid rgba(148,163,184,0.16);
            padding: clamp(1.8rem, 4vw, 2.3rem);
            box-shadow: 0 32px 70px -50px rgba(15,23,42,0.28);
            backdrop-filter: blur(12px);
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

        .cta-scale {
            position: relative;
            overflow: hidden;
            border-radius: 40px;
            padding: clamp(3rem, 6vw, 4rem) clamp(2rem, 6vw, 4.5rem);
            background:
                radial-gradient(120% 140% at 20% -40%, rgba(56,189,248,0.32), transparent 65%),
                radial-gradient(120% 140% at 90% -30%, rgba(14,165,233,0.24), transparent 70%),
                linear-gradient(135deg, rgba(15,23,42,0.02) 0%, rgba(244,251,255,0.88) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 52px 120px -68px rgba(15,23,42,0.32);
        }

        .cta-scale::before {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(115deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 18px);
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
            display: inline-flex;
            gap: 0.9rem;
            align-items: center;
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
            .network-section {
                padding: clamp(2.4rem, 8vw, 3.2rem);
                border-radius: 32px;
            }
            .network-grid {
                gap: 2rem;
            }
            .network-steps {
                gap: 1rem;
            }
            .network-step {
                padding: 1rem 1.1rem;
            }
            .network-metric {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.4rem;
            }
        }
    </style>

    <section class="hero-simplify mb-5" style="margin-left:auto;margin-right:auto;">
        <div class="hero-top text-center">
            @if($heroBadge)
                <span class="hero-badge">
                    <span class="mdi mdi-sparkles"></span>
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
    </section>

    <section class="site-section">
        <header class="site-section-header text-center mb-4">
            <p class="site-eyebrow mb-2">Platform pillars</p>
            <h2 class="display-6 fw-semibold">Deploy AI-grade infrastructure for every conversation</h2>
            <a href="{{ route('stories') }}" class="site-link mt-2 d-inline-flex align-items-center gap-1">
                See customer stories <span class="mdi mdi-arrow-right"></span>
            </a>
        </header>
        <div class="row g-4">
            @foreach($featureItems as $feature)
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        @if($icon = data_get($feature, 'icon'))
                            <div class="mb-3" style="width:46px;height:46px;border-radius:14px;background:rgba(14,165,233,0.15);display:grid;place-items:center;color:rgba(14,165,233,0.75);">
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

    <section class="site-section network-section">
        <div class="network-grid">
            <div class="network-left">
                <span class="network-eyebrow">Network operating system</span>
                <h2 class="display-6 fw-semibold network-title">{{ $ritual->title ?? 'Blueprint every touchpoint, from outreach to deep partnerships.' }}</h2>
                <p class="network-copy">{{ $ritual->subtitle ?? 'CoffeeChat OS distills the playbooks of top relationship builders into four repeatable motions. Personalise, automate, and scale them with AI copilots.' }}</p>
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
                    <div class="network-pane">
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
                            <a href="{{ data_get($job, 'apply_url') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Apply</a>
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
                            <a href="{{ data_get($job, 'apply_url') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Apply</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="site-section site-section--transparent cta-scale mt-4">
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

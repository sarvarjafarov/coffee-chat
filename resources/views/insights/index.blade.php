@extends('layouts.site')

@section('content')
    @php
        $hero = $components['hero'] ?? null;
        $heroMeta = $hero?->meta ?? [];
        $chips = collect(data_get($heroMeta, 'chips', []));

        $highlights = $components['highlights'] ?? null;
        $highlightCards = collect(data_get($highlights?->meta, 'cards', []));

        $metrics = $components['metrics'] ?? null;
        $metricItems = collect(data_get($metrics?->meta, 'metrics', []));
    @endphp

    <style>
        .insights-hero {
            position: relative;
            background:
                radial-gradient(140% 180% at 10% -20%, rgba(56,189,248,0.26), transparent 68%),
                radial-gradient(120% 160% at 88% -10%, rgba(14,165,233,0.22), transparent 70%),
                linear-gradient(180deg, rgba(244,251,255,0.97) 0%, rgba(233,244,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 48px 110px -62px rgba(15,23,42,0.26);
            overflow: hidden;
        }

        .insights-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(110deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 24px);
            opacity: 0.45;
            pointer-events: none;
        }

        .insights-hero-inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: clamp(2rem, 4vw, 3rem);
            align-items: flex-start;
        }

        .insights-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .insights-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(148,163,184,0.24);
            color: rgba(37,99,235,0.85);
            font-weight: 600;
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: clamp(1.6rem, 3vw, 2.4rem);
        }

        .insight-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 26px;
            padding: clamp(1.9rem, 4vw, 2.4rem);
            box-shadow: 0 32px 72px -52px rgba(15,23,42,0.2);
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .insight-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .insight-card .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(37,99,235,0.75);
            box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
        }

        .insight-card .meta-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            color: rgba(71,85,105,0.68);
        }

        .metrics-shell {
            background:
                radial-gradient(140% 200% at 8% -30%, rgba(56,189,248,0.26), transparent 68%),
                linear-gradient(180deg, rgba(15,23,42,0.95) 0%, rgba(30,41,59,0.88) 100%);
            border-radius: 32px;
            border: 1px solid rgba(148,163,184,0.25);
            box-shadow: 0 42px 100px -58px rgba(15,23,42,0.38);
            padding: clamp(2.4rem, 5vw, 3.1rem);
            color: rgba(226,232,240,0.92);
        }

        .metrics-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: clamp(1.6rem, 3vw, 2.4rem);
            margin-top: clamp(1.8rem, 4vw, 2.6rem);
        }

        .metric-card {
            background: rgba(255,255,255,0.08);
            border-radius: 22px;
            padding: 1.6rem;
            border: 1px solid rgba(148,163,184,0.22);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .metric-card p {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            color: rgba(226,232,240,0.72);
        }

        .metric-value {
            font-size: 2.3rem;
            font-weight: 700;
            color: #fff;
        }

        .metric-change {
            margin-top: 0.35rem;
            font-weight: 600;
        }
    </style>

    <section class="site-section insights-hero mb-5">
        <div class="insights-hero-inner">
            <div>
                <p class="site-eyebrow mb-2">Insights</p>
                <h1 class="display-5 fw-semibold mb-3">{{ $hero->title ?? 'Insights Dashboard' }}</h1>
                <p class="lead text-subtle mb-0">{{ $hero->subtitle ?? 'Macro trends across the CoffeeChat OS network. Track evolving outreach patterns, industries heating up, and response benchmarks.' }}</p>
            </div>
            @if($chips->isNotEmpty())
                <div class="insights-chip-row">
                    @foreach($chips as $chip)
                        <span class="insights-chip"><span class="mdi mdi-pulse"></span>{{ $chip }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="site-section">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h2 class="h4 fw-semibold mb-0">{{ $highlights->title ?? "This week's signal" }}</h2>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Receive weekly briefing</a>
        </div>
        <div class="insights-grid">
            @forelse($highlightCards as $card)
                <article class="insight-card">
                    <div class="meta-row">
                        <span class="dot"></span>
                        <span>{{ strtoupper(data_get($card, 'tag', 'Highlight')) }}</span>
                    </div>
                    <h3>{{ data_get($card, 'title') }}</h3>
                    <p class="text-subtle mb-0">{{ data_get($card, 'body') }}</p>
                </article>
            @empty
                <div class="alert alert-info mb-0">Add highlight cards from the dashboard to bring this section to life.</div>
            @endforelse
        </div>
    </section>

    <section class="site-section site-section--transparent">
        <div class="metrics-shell">
            <div class="metrics-top">
                <div>
                    <p class="site-eyebrow mb-2 text-white-50">Pipeline metrics</p>
                    <h2 class="h4 fw-semibold mb-0 text-white">{{ $metrics->title ?? 'Pipeline performance' }}</h2>
                </div>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">
                    <span class="mdi mdi-view-dashboard-outline me-1"></span>
                    Launch your dashboard
                </a>
            </div>
            <div class="metrics-grid">
                @forelse($metricItems as $item)
                    <div class="metric-card">
                        <p class="mb-2">{{ data_get($item, 'label') }}</p>
                        <span class="metric-value">{{ data_get($item, 'value') }}</span>
                        @if($change = data_get($item, 'change'))
                            <p class="metric-change {{ str_contains($change, '-') ? 'text-danger' : 'text-success' }}">{{ $change }}</p>
                        @endif
                    </div>
                @empty
                    <div class="alert alert-info mb-0">Add metrics via the dashboard to visualise progress.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

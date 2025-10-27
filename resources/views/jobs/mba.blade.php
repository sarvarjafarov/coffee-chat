@extends('layouts.site')

@section('content')
    @php
        $jobs = ($jobs ?? collect())->map(fn ($job) => (object) $job);
        $internships = ($internships ?? collect())->map(fn ($job) => (object) $job);
    @endphp

    <style>
        .mba-hero {
            position: relative;
            background:
                radial-gradient(140% 170% at 10% -20%, rgba(56,189,248,0.26), transparent 70%),
                radial-gradient(120% 160% at 90% -20%, rgba(14,165,233,0.2), transparent 72%),
                linear-gradient(180deg, rgba(244,251,255,0.97) 0%, rgba(233,244,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 52px 120px -68px rgba(15,23,42,0.28);
            overflow: hidden;
        }

        .mba-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(115deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 22px);
            opacity: 0.45;
            pointer-events: none;
        }

        .mba-hero-inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: clamp(2rem, 4vw, 3rem);
            align-items: center;
        }

        .mba-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.15);
            color: rgba(14,165,233,0.85);
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .mba-hero h1 {
            font-size: clamp(2.45rem, 3.2vw + 1rem, 3.8rem);
            font-weight: 700;
        }

        .mba-hero-copy p {
            max-width: 46rem;
        }

        .mba-hero-meta {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .mba-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.85rem 2rem;
            background: linear-gradient(120deg, rgba(14,165,233,0.95), rgba(37,99,235,0.85));
            color: #fff;
            font-weight: 600;
            box-shadow: 0 28px 58px -32px rgba(15,23,42,0.28);
        }

        .mba-cta:hover {
            color: #fff;
            transform: translateY(-2px);
        }

        .mba-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(341px, 1fr));
            gap: clamp(1.4rem, 3vw, 2.1rem);
        }

        .mba-section-shell {
            padding: clamp(1.8rem, 4vw, 2.4rem) clamp(2.4rem, 8vw, 4.2rem);
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.9) 100%);
            border-radius: 28px;
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 32px 70px -52px rgba(15,23,42,0.22);
        }

        .mba-section-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: clamp(1.6rem, 3vw, 2.2rem);
        }

        .mba-card {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 24px;
            padding: clamp(1.4rem, 4vw, 1.8rem);
            box-shadow: 0 30px 64px -48px rgba(15,23,42,0.18);
        }

        .mba-card header {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .mba-card img {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(148,163,184,0.18);
            background: #fff;
            object-fit: cover;
        }

        .mba-card h2 {
            font-size: 1.08rem;
            font-weight: 600;
            margin-bottom: 0.15rem;
        }

        .mba-card .location {
            font-size: 0.9rem;
            color: rgba(71,85,105,0.8);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .mba-card .description {
            color: rgba(71,85,105,0.85);
            line-height: 1.55;
            flex-grow: 1;
        }

        .mba-card .apply-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.7rem 1.6rem;
            font-weight: 600;
            background: rgba(14,165,233,0.14);
            border: 1px solid rgba(14,165,233,0.32);
            color: rgba(14,165,233,0.92);
        }

        .mba-card .apply-btn:hover {
            background: rgba(14,165,233,0.2);
            color: var(--accent-strong);
        }

        .mba-card .follow-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.7rem 1.2rem;
            font-weight: 600;
            background: rgba(37,99,235,0.12);
            border: 1px solid rgba(37,99,235,0.28);
            color: rgba(37,99,235,0.92);
            transition: all 0.2s ease;
        }

        .mba-card .follow-btn:hover {
            background: rgba(37,99,235,0.18);
            color: var(--accent-strong);
        }

        .mba-card .action-group {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }
    </style>

    <section class="site-section mba-hero mb-5">
        <div class="mba-hero-inner">
            <div class="mba-hero-copy">
                <span class="mba-hero-badge"><span class="mdi mdi-briefcase-outline"></span> MBA Jobs</span>
                <h1 class="mt-3 mb-3">Curated full-time roles for MBA operators.</h1>
                <p class="lead text-subtle mb-0">
                    We monitor venture-backed companies, operating roles, and growth-stage teams hiring MBA talent. Each listing is hand picked with a direct application link.
                </p>
            </div>
            <div class="mba-hero-meta">
                <a href="#mba-openings" class="mba-cta">
                    <span class="mdi mdi-magnify"></span>
                    Browse open roles
                </a>
                <span class="text-subtle">Updated weekly · Filtered for strategic, operating, and leadership tracks.</span>
            </div>
        </div>
    </section>

    <section id="mba-openings" class="site-section">
        <div class="mba-section-shell">
            <div class="mba-section-header">
                <h2 class="h4 fw-semibold mb-0">Latest full-time openings</h2>
                <span class="text-subtle small">{{ $jobs->count() }} roles curated for MBA operators</span>
            </div>
            <div class="mba-grid">
            @foreach($jobs as $job)
                <article class="mba-card">
                    <header>
                        @if($job->logo)
                            <img src="{{ $job->logo }}" alt="{{ $job->company }} logo">
                        @else
                            <span class="mdi mdi-briefcase-outline fs-2 text-primary"></span>
                        @endif
                        <div>
                            <h2 class="mb-1">{{ $job->title }}</h2>
                            <span class="text-subtle">{{ $job->company }}</span>
                        </div>
                    </header>
                    <span class="location">
                        <span class="mdi mdi-map-marker-outline"></span>
                        {{ $job->location }}
                    </span>
                    <p class="description mb-0">{{ $job->description }}</p>
                    @php
                        $city = trim(\Illuminate\Support\Str::before($job->location, '·'));
                        $teamFinderUrl = route('workspace.team-finder.index', array_filter([
                            'position' => $job->title,
                            'company' => $job->company,
                            'city' => $city,
                        ], fn ($value) => filled($value)));
                    @endphp
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-auto">
                        <span class="text-subtle small">
                            <span class="mdi mdi-check-circle-outline"></span>
                            Direct apply
                        </span>
                        <div class="action-group">
                            <a href="{{ $teamFinderUrl }}" class="follow-btn">
                                <span class="mdi mdi-account-search-outline"></span>
                                Follow role
                            </a>
                            <a href="{{ $job->apply_url }}" target="_blank" rel="noopener" class="apply-btn">
                                <span class="mdi mdi-open-in-new"></span>
                                Apply now
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
            </div>
        </div>
    </section>

    @if($internships->isNotEmpty())
        <section id="mba-internships" class="site-section">
            <div class="mba-section-shell">
                <div class="mba-section-header">
                    <h2 class="h4 fw-semibold mb-0">MBA internship opportunities</h2>
                    <span class="text-subtle small">{{ $internships->count() }} internships for Summer 2025</span>
                </div>
                <div class="mba-grid">
                @foreach($internships as $job)
                    <article class="mba-card">
                        <header>
                            @if(data_get($job, 'logo'))
                                <img src="{{ data_get($job, 'logo') }}" alt="{{ data_get($job, 'company') }} logo">
                            @else
                                <span class="mdi mdi-domain text-primary fs-4"></span>
                            @endif
                            <div>
                                <h2 class="mb-1">{{ data_get($job, 'title') }}</h2>
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
                        <p class="description mb-0">{{ data_get($job, 'description') }}</p>
                        @php
                            $internCity = trim(\Illuminate\Support\Str::before(data_get($job, 'location', ''), '·'));
                            $internFinderUrl = route('workspace.team-finder.index', array_filter([
                                'position' => data_get($job, 'title'),
                                'company' => data_get($job, 'company'),
                                'city' => $internCity,
                            ], fn ($value) => filled($value)));
                        @endphp
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-auto">
                            <span class="text-subtle small">
                                <span class="mdi mdi-check-circle-outline"></span>
                                Direct apply
                            </span>
                            <div class="action-group">
                                <a href="{{ $internFinderUrl }}" class="follow-btn">
                                    <span class="mdi mdi-account-search-outline"></span>
                                    Follow role
                                </a>
                                <a href="{{ data_get($job, 'apply_url') }}" target="_blank" rel="noopener" class="apply-btn">
                                    <span class="mdi mdi-open-in-new"></span>
                                    Apply now
                                </a>
                            </div>
                    </div>
                    </article>
                @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

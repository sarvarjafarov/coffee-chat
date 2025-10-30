@extends('workspace.layout')

@section('workspace-content')
    @php
        $filters = $filters ?? [];
        $hasFilters = $hasFilters ?? false;
        $results = collect($results ?? []);
        $recommended = collect($recommended ?? []);
        $summary = $summary ?? ['total' => 0, 'by_source' => []];
        $diagnostics = collect($diagnostics ?? []);
    @endphp

    <style>
        .team-finder-hero {
            display: grid;
            gap: clamp(2rem, 4vw, 2.8rem);
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            align-items: center;
        }

        .team-finder-heading {
            font-size: clamp(2.1rem, 3vw, 3rem);
            font-weight: 700;
            line-height: 1.1;
            color: var(--text-primary);
        }

        .team-finder-heading .highlight {
            display: inline-block;
            padding-bottom: 0.2em;
            background: linear-gradient(120deg, rgba(14,165,233,0.2), rgba(14,165,233,0.06));
            border-bottom: 6px solid rgba(14,165,233,0.65);
        }

        .team-finder-subcopy {
            color: rgba(71,85,105,0.85);
            max-width: 34rem;
            font-size: 1.05rem;
        }

        .team-finder-illustration {
            border-radius: 28px;
            border: 1px solid rgba(148,163,184,0.18);
            background: linear-gradient(180deg, rgba(244,251,255,0.96) 0%, rgba(233,244,255,0.9) 100%);
            padding: clamp(2rem, 4vw, 3rem);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            box-shadow: 0 32px 70px -48px rgba(15,23,42,0.26);
        }

        .team-finder-illustration span {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.6rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 600;
            font-size: 1rem;
        }

        .team-finder-result {
            border-radius: 24px;
            border: 1px solid rgba(148,163,184,0.18);
            background: rgba(255,255,255,0.95);
            padding: clamp(1.4rem, 3vw, 1.8rem);
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            box-shadow: 0 30px 70px -50px rgba(15,23,42,0.22);
        }

        .team-finder-result h3 {
            margin-bottom: 0.3rem;
            font-size: 1.18rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .team-finder-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(148,163,184,0.16);
            background: rgba(248,250,252,0.9);
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(71,85,105,0.8);
        }

        .team-finder-chip .mdi {
            font-size: 1rem;
        }

        .team-finder-chip--source {
            background: rgba(14,165,233,0.1);
            color: rgba(14,165,233,0.85);
            border-color: rgba(14,165,233,0.22);
        }

        .team-finder-chip--confidence {
            background: rgba(30,41,59,0.06);
            border-color: rgba(148,163,184,0.18);
        }

        .team-finder-meta {
            display: grid;
            gap: 0.35rem;
            color: rgba(71,85,105,0.85);
            font-size: 0.94rem;
        }

        .team-finder-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.32rem 0.9rem;
            border-radius: 999px;
            border: 1px solid rgba(14,165,233,0.24);
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 600;
            font-size: 0.82rem;
        }

        .team-finder-actions a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            color: var(--accent-strong);
            text-decoration: none;
        }

        .team-finder-actions a:hover {
            color: var(--accent);
        }

        .team-finder-actions form {
            display: inline-flex;
            margin: 0;
        }

        .team-finder-actions button {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            border-radius: 999px;
            border: 1px solid rgba(14,165,233,0.3);
            background: rgba(14,165,233,0.1);
            color: rgba(14,165,233,0.9);
            padding: 0.32rem 0.9rem;
            transition: all 0.2s ease;
        }

        .team-finder-actions button .mdi {
            font-size: 1rem;
        }

        .team-finder-actions button:hover {
            border-color: rgba(14,165,233,0.45);
            background: rgba(14,165,233,0.16);
            color: rgba(14,165,233,1);
        }

        .team-finder-empty {
            text-align: center;
            padding: clamp(2.4rem, 5vw, 3.2rem);
            background: rgba(14,165,233,0.08);
            border-radius: 26px;
            border: 1px dashed rgba(14,165,233,0.3);
        }

        .team-finder-empty h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .team-finder-empty p {
            color: rgba(71,85,105,0.85);
        }
    </style>

    <div class="workspace-card workspace-section">
        <div class="team-finder-hero">
            <div>
                <span class="workspace-eyebrow">Team finder</span>
                <h1 class="team-finder-heading mb-3">
                    Find the <span class="highlight">right teammates</span> for every outreach.
                </h1>
                <p class="team-finder-subcopy mb-0">
                    Filter your saved network by role, company, location, and team so you can reach out with confidence. Jump straight to LinkedIn or email when you’re ready to connect.
                </p>
            </div>
            <div class="team-finder-illustration">
                <span>
                    <i class="mdi mdi-account-search-outline"></i>
                    Personalized matches in seconds
                </span>
            </div>
        </div>
    </div>

@if(session('status'))
        <div class="alert alert-success workspace-section mb-0">
            {{ session('status') }}
        </div>
@endif

@isset($statusMessage)
    <div class="alert alert-warning workspace-section mb-0">
        {{ $statusMessage }}
    </div>
@endisset

    <div class="workspace-card workspace-section workspace-form">
        <form
            action="{{ route('workspace.team-finder.index') }}"
            method="GET"
            data-team-finder-form
            data-search-endpoint="{{ route('workspace.team-finder.search') }}"
            data-follow-action="{{ route('workspace.team-finder.follow') }}"
        >
            <div class="row g-3">
                <div class="col-md-6 col-xl-3">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" class="form-control" placeholder="e.g. Product Manager" value="{{ $filters['position'] ?? '' }}">
                </div>
                <div class="col-md-6 col-xl-3">
                    <label for="company">Company name</label>
                    <input type="text" id="company" name="company" class="form-control" placeholder="e.g. Google" value="{{ $filters['company'] ?? '' }}">
                </div>
                <div class="col-md-6 col-xl-3">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="e.g. New York" value="{{ $filters['city'] ?? '' }}">
                </div>
                <div class="col-md-6 col-xl-3">
                    <label for="team_name">Team name</label>
                    <input type="text" id="team_name" name="team_name" class="form-control" placeholder="e.g. YouTube Ads" value="{{ $filters['team_name'] ?? '' }}">
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <span class="mdi mdi-magnify me-2"></span>
                    Search contacts
                </button>
                @if($hasFilters)
                    <a href="{{ route('workspace.team-finder.index') }}" class="btn btn-outline-secondary">
                        Clear filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="workspace-section" data-team-finder-top-wrapper>
        @if($recommended->isNotEmpty())
            <div class="workspace-card">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <span class="workspace-eyebrow text-uppercase">Top matches</span>
                        <h2 class="h5 mb-1">Best prospects to reach out to first</h2>
                        @if(($summary['total'] ?? 0) > 0)
                            @php
                                $sourceLabels = collect(array_keys($summary['by_source'] ?? []))
                                    ->map(fn ($label) => str($label)->replace('_', ' ')->title())
                                    ->all();
                            @endphp
                            <p class="text-subtle mb-0">
                                {{ $summary['total'] }} total matches surfaced from
                                {{ \Illuminate\Support\Arr::join($sourceLabels, ', ', ' and ') }}.
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row g-3 mt-3" data-team-finder-top>
                    @foreach($recommended as $item)
                        @php
                            $itemSnippet = $item['snippet'] ?? $item['primary_reason'] ?? null;
                            $itemConfidence = isset($item['confidence']) ? (int) round(($item['confidence'] ?? 0) * 100) : null;
                        @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="team-finder-result h-100">
                                <div>
                                    <h3 class="mb-1">{{ $item['name'] ?? 'Potential contact' }}</h3>
                                    <div class="team-finder-meta">
                                        <span>
                                            {{ $item['company'] ?? $filters['company'] ?? 'Unknown company' }}
                                            @if(!empty($item['role']))
                                                · {{ $item['role'] }}
                                            @endif
                                        </span>
                                        @if(!empty($item['team']))
                                            <span>{{ $item['team'] }}</span>
                                        @endif
                                        @if(!empty($item['location']))
                                            <span>{{ $item['location'] }}</span>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @if(!empty($item['source']))
                                                <span class="team-finder-chip team-finder-chip--source">
                                                    <i class="mdi mdi-radar"></i>
                                                    {{ str($item['source'] ?? 'unknown')->replace('_', ' ')->title() }}
                                                </span>
                                            @endif
                                            @if($itemConfidence !== null)
                                                <span class="team-finder-chip team-finder-chip--confidence">
                                                    <i class="mdi mdi-target"></i>
                                                    {{ $itemConfidence }}% confidence
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="team-finder-actions d-flex flex-column gap-3 mt-auto">
                                    @if($itemSnippet)
                                        <p class="mb-0 text-sm text-slate-600">{{ \Illuminate\Support\Str::of($itemSnippet)->limit(220) }}</p>
                                    @endif
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(!empty($item['url']))
                                            <a href="{{ $item['url'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-open-in-new"></span>
                                                View profile
                                            </a>
                                        @endif
                                        <form action="{{ route('workspace.team-finder.follow') }}" method="POST" class="d-inline" data-follow-coffee-chat>
                                            @csrf
                                            <input type="hidden" name="contact" value='@json($item)'>
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <span class="mdi mdi-send"></span>
                                                Follow for coffee chat
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-result='@json($item)'>
                                            <span class="mdi mdi-note-plus-outline"></span>
                                            Add note
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="workspace-section" data-team-finder-diagnostics-wrapper>
        @if($diagnostics->isNotEmpty())
            <div class="alert alert-info mb-0" data-team-finder-diagnostics>
                <strong>Provider status</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($diagnostics as $diagnostic)
                        <li>
                            <span class="fw-semibold">{{ str($diagnostic['source'] ?? 'unknown')->replace('_', ' ')->title() }}:</span>
                            @if(($diagnostic['status'] ?? '') === 'success')
                                {{ $diagnostic['count'] ?? 0 }} match(es) returned.
                            @elseif(($diagnostic['status'] ?? '') === 'no_results')
                                No matches returned for this query.
                            @else
                                {{ $diagnostic['message'] ?? 'Request failed.' }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="workspace-section" data-team-finder-results-wrapper>
        <div class="team-finder-message mb-4" data-team-finder-message>
            @isset($statusMessage)
                <div class="alert alert-warning mb-0">{{ $statusMessage }}</div>
            @endisset
        </div>

        @if($hasFilters)
            @if($results->isNotEmpty())
                <div class="row g-4" data-team-finder-results>
                    @foreach($results as $result)
                        <div class="col-md-6 col-xl-4">
                            <div class="team-finder-result h-100">
                                <div>
                                    <h3>{{ $result['name'] ?? 'Potential contact' }}</h3>
                                    <div class="team-finder-meta">
                                        <span>
                                            {{ $result['company'] ?? $filters['company'] ?? 'Unknown company' }}
                                            @if(!empty($result['role']))
                                                · {{ $result['role'] }}
                                            @endif
                                        </span>
                                        @php
                                            $resultSnippet = $result['snippet'] ?? $result['primary_reason'] ?? null;
                                            $resultConfidence = isset($result['confidence']) ? (int) round(($result['confidence'] ?? 0) * 100) : null;
                                        @endphp
                                        @if(!empty($result['team']))
                                            <span>{{ $result['team'] }}</span>
                                        @endif
                                        @if(!empty($result['location']))
                                            <span>{{ $result['location'] }}</span>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @if(!empty($result['source']))
                                                <span class="team-finder-chip team-finder-chip--source">
                                                    <i class="mdi mdi-radar"></i>
                                                    {{ str($result['source'])->replace('_', ' ')->title() }}
                                                </span>
                                            @endif
                                            @if($resultConfidence !== null)
                                                <span class="team-finder-chip team-finder-chip--confidence">
                                                    <i class="mdi mdi-target"></i>
                                                    {{ $resultConfidence }}% confidence
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="team-finder-actions d-flex flex-column gap-3 mt-auto">
                                    @if($resultSnippet)
                                        <p class="mb-0 text-sm text-slate-600">{{ \Illuminate\Support\Str::of($resultSnippet)->limit(220) }}</p>
                                    @endif
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(!empty($result['url']))
                                            <a href="{{ $result['url'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-open-in-new"></span>
                                                View profile
                                            </a>
                                        @endif
                                        <form action="{{ route('workspace.team-finder.follow') }}" method="POST" class="d-inline" data-follow-coffee-chat>
                                            @csrf
                                            <input type="hidden" name="contact" value='@json($result)'>
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <span class="mdi mdi-send"></span>
                                                Follow for coffee chat
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-result='@json($result)'>
                                            <span class="mdi mdi-note-plus-outline"></span>
                                            Add note
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($scrapeAttempted)
                <div class="team-finder-empty text-center py-5">
                    <h2 class="h5">No leads yet</h2>
                    <p class="text-subtle">We couldn’t find anyone matching those filters. Adjust the search or try again later.</p>
                </div>
            @else
                <div class="team-finder-empty text-center py-5" data-team-finder-placeholder>
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h2 class="h5">Gathering leads...</h2>
                    <p class="text-subtle">We’re sourcing potential teammates in real-time. Stay on the page and we’ll drop them in below.</p>
                </div>
            @endif
        @else
            <div class="team-finder-empty text-center py-5" data-team-finder-placeholder>
                <h2 class="h5">Start with a company &amp; role</h2>
                <p class="text-subtle mb-0">Enter a company and position to uncover warm coffee-chat prospects from across the web.</p>
            </div>
        @endif
        <small class="text-subtle d-block mt-3" data-team-finder-footnote>External leads refresh automatically when you tweak filters. Top matches blend your workspace data with live search results.</small>
    </div>

@endsection

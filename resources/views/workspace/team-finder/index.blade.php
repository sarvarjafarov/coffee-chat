@extends('workspace.layout')

@section('workspace-content')
    @php
        $filters = $filters ?? [];
        $contacts = $contacts ?? collect();
        $hasFilters = $hasFilters ?? false;
        $isPaginator = $contacts instanceof \Illuminate\Contracts\Pagination\Paginator;
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

    <div class="workspace-card workspace-section workspace-form">
        <form action="{{ route('workspace.team-finder.index') }}" method="GET">
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

    <div class="workspace-section" data-results="{{ $hasFilters ? 'true' : 'false' }}">
        @if($hasFilters)
            @if($results->isEmpty())
                @if($scrapeAttempted)
                    <div class="team-finder-empty text-center py-5">
                        <h2 class="h5">No leads yet</h2>
                        <p class="text-subtle">We couldn’t find anyone matching those filters. Adjust the search or try again later.</p>
                    </div>
                @else
                    <div class="team-finder-empty text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <h2 class="h5">Gathering leads...</h2>
                        <p class="text-subtle">We’re sourcing potential teammates in real-time. Stay on the page and we’ll drop them in below.</p>
                    </div>
                @endif
            @else
                <div class="row g-4">
                    @foreach($results as $result)
                        <div class="col-md-6 col-xl-4">
                            <div class="team-finder-result h-100">
                                <div>
                                    <h3>{{ $result->name() ?? 'Unknown contact' }}</h3>
                                    <div class="team-finder-meta">
                                        <span>{{ $result->company() }} · {{ $result->position() }}</span>
                                        @if($result->team())
                                            <span>{{ $result->team() }}</span>
                                        @endif
                                        @if($result->location())
                                            <span>{{ $result->location() }}</span>
                                        @endif
                                        @if($result->source())
                                            <span class="team-finder-pill">
                                                <i class="mdi mdi-radar"></i>
                                                {{ ucfirst($result->source()) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="team-finder-actions d-flex flex-wrap gap-3 mt-auto">
                                    @if($result->type === 'contact')
                                        @php($contact = $result->contact)
                                        @php($existingChat = $contact?->coffeeChats->first())
                                        @if($existingChat)
                                            <a href="{{ route('workspace.coffee-chats.edit', $existingChat) }}" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-pencil-outline"></span>
                                                Manage coffee chat
                                            </a>
                                        @else
                                            <form action="{{ route('workspace.team-finder.coffee-chats.store', $contact) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="position_title" value="{{ $contact->position ?? ($filters['position'] ?? '') }}">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <span class="mdi mdi-coffee-outline"></span>
                                                    Add to coffee chats
                                                </button>
                                            </form>
                                        @endif
                                        @if($result->profileUrl())
                                            <a href="{{ $result->profileUrl() }}" target="_blank" rel="noopener">
                                                <span class="mdi mdi-linkedin"></span>
                                                LinkedIn
                                            </a>
                                        @endif
                                        @if($result->email())
                                            <a href="mailto:{{ $result->email() }}" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-email-outline"></span>
                                                Email
                                            </a>
                                        @endif
                                    @else
        
                                        @if($result->profileUrl())
                                            <a href="{{ $result->profileUrl() }}" target="_blank" rel="noopener">
                                                <span class="mdi mdi-open-in-new"></span>
                                                Profile
                                            </a>
                                        @endif
                                        @if($result->email())
                                            <a href="mailto:{{ $result->email() }}" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-email-outline"></span>
                                                Email
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-primary">
                                                <span class="mdi mdi-send"></span>
                                                Send
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                                <span class="mdi mdi-reply"></span>
                                                Follow-up
                                            </button>
                                        @endif
                                        @if($result->scrapedAt())
                                            <span class="text-subtle small">Scraped {{ $result->scrapedAt() }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($contacts instanceof \Illuminate\Contracts\Pagination\Paginator && $contacts->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $contacts->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        @else
            <div class="team-finder-empty">
                <h2>No matches yet</h2>
                <p>Adjust your filters or log more contacts to expand your talent map.</p>
            </div>
        @endif
        <small class="text-subtle d-block mt-3">Leads refresh automatically every 10 minutes when you tweak filters.</small>
    </div>
@endsection

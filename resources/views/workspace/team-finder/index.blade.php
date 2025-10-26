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

    @if($contacts && $contacts->count())
        <div class="workspace-section">
            <div class="row g-4">
                @foreach($contacts as $contact)
                    <div class="col-md-6 col-xl-4">
                        <div class="team-finder-result h-100">
                            <div>
                                <h3>{{ $contact->name }}</h3>
                                <div class="team-finder-meta">
                                    @if($contact->company)
                                        <span>{{ $contact->company->name }} · {{ $contact->position ?? '—' }}</span>
                                    @else
                                        <span>{{ $contact->position ?? '—' }}</span>
                                    @endif
                                    @if($contact->city || $contact->country)
                                        <span>{{ trim($contact->city . ', ' . $contact->country, ', ') }}</span>
                                    @endif
                                    @if($contact->team_name)
                                        <span class="team-finder-pill">
                                            <i class="mdi mdi-account-multiple-outline"></i>
                                            {{ $contact->team_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="team-finder-actions d-flex flex-wrap gap-3 mt-auto">
                                @if($contact->linkedin_url)
                                    <a href="{{ $contact->linkedin_url }}" target="_blank" rel="noopener">
                                        <span class="mdi mdi-linkedin"></span>
                                        LinkedIn
                                    </a>
                                @endif
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}">
                                        <span class="mdi mdi-email-outline"></span>
                                        Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($isPaginator)
                <div class="mt-4 d-flex justify-content-center">
                    {{ $contacts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="team-finder-empty workspace-section">
            <h2>No matches yet</h2>
            <p>Adjust your filters or log more contacts to expand your talent map.</p>
        </div>
    @endif
@endsection

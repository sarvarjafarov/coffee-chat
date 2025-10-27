@extends('layouts.site')

@php
    $hasResult = isset($score);
@endphp

@section('content')
    <section class="site-section">
        <style>
            .workspace-card {
                background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(244,251,255,0.92) 100%);
                border: 1px solid rgba(148,163,184,0.16);
                border-radius: 28px;
                padding: clamp(1.8rem, 4vw, 2.4rem);
                box-shadow: 0 36px 76px -50px rgba(15,23,42,0.18);
            }
            .workspace-form .form-label {
                font-weight: 600;
                color: rgba(71,85,105,0.85);
            }
            .workspace-form .form-control,
            .workspace-form .form-select {
                border-radius: 14px;
                background: rgba(255,255,255,0.96);
                border-color: rgba(148,163,184,0.28);
            }
            .workspace-divider {
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(148,163,184,0.25), transparent);
                margin: clamp(1.5rem, 3vw, 2.4rem) 0;
            }
        </style>
        <div class="mb-4">
            <span class="site-eyebrow d-inline-flex align-items-center gap-2">
                <span class="mdi mdi-pulse"></span> Network health analysis
            </span>
            <h1 class="display-5 fw-semibold mt-3 mb-3">Measure the strength of your relationship graph.</h1>
            <p class="lead text-subtle mb-0" style="max-width:42rem;">
                Grounded in the <strong>six degrees of separation</strong> research popularised by Milgram and Travers, this quick analysis estimates how resilient your network is by looking at density, diversity, and follow-through behaviours.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="workspace-card workspace-form">
                    <h2 class="h4 fw-semibold mb-3">Get your score</h2>
                    <p class="text-subtle small mb-4">It’s free. Enter your email and answer five questions—we’ll instantly calculate a score out of 100 and highlight where to improve.</p>
                    <form method="POST" action="{{ route('network-health.analyze') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Work or personal email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="monthly_unique_contacts" class="form-label">How many unique contacts do you actively engage with each month?</label>
                            <input type="number" class="form-control @error('monthly_unique_contacts') is-invalid @enderror" id="monthly_unique_contacts" name="monthly_unique_contacts" min="0" max="200" value="{{ old('monthly_unique_contacts', $answers['monthly_unique_contacts'] ?? 25) }}" required>
                            <small class="text-subtle">Frequent touchpoints shrink your average path length.</small>
                            @error('monthly_unique_contacts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="warm_intros_last_quarter" class="form-label">Warm introductions secured in the last quarter</label>
                            <input type="number" class="form-control @error('warm_intros_last_quarter') is-invalid @enderror" id="warm_intros_last_quarter" name="warm_intros_last_quarter" min="0" max="50" value="{{ old('warm_intros_last_quarter', $answers['warm_intros_last_quarter'] ?? 6) }}" required>
                            <small class="text-subtle">More intros signal denser networks and shorter degrees of separation.</small>
                            @error('warm_intros_last_quarter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="average_follow_up_days" class="form-label">Average days to send follow-up after a meeting</label>
                            <input type="number" step="0.1" class="form-control @error('average_follow_up_days') is-invalid @enderror" id="average_follow_up_days" name="average_follow_up_days" min="0" max="30" value="{{ old('average_follow_up_days', $answers['average_follow_up_days'] ?? 4) }}" required>
                            <small class="text-subtle">Faster follow-ups reinforce tie strength.</small>
                            @error('average_follow_up_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="industry_diversity" class="form-label">How diverse are the industries represented in your close network?</label>
                            <select class="form-select @error('industry_diversity') is-invalid @enderror" id="industry_diversity" name="industry_diversity" required>
                                @foreach(range(1,5) as $value)
                                    <option value="{{ $value }}" {{ (int) old('industry_diversity', $answers['industry_diversity'] ?? 3) === $value ? 'selected' : '' }}>
                                        {{ $value }} — {{ ['Single industry focus','Mostly two fields','Balanced mix','Very diverse','Cross-industry super connector'][$value-1] }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-subtle">Higher diversity increases your clustering coefficient.</small>
                            @error('industry_diversity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="relationship_strength" class="form-label">How strong are your relationships overall?</label>
                            <select class="form-select @error('relationship_strength') is-invalid @enderror" id="relationship_strength" name="relationship_strength" required>
                                @foreach(range(1,5) as $value)
                                    <option value="{{ $value }}" {{ (int) old('relationship_strength', $answers['relationship_strength'] ?? 3) === $value ? 'selected' : '' }}>
                                        {{ $value }} — {{ ['Mostly dormant','Occasional touchpoints','Consistent check-ins','Strong ties','Go-to collaborator'][ $value -1 ] }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-subtle">Strong ties reduce friction when traversing your network graph.</small>
                            @error('relationship_strength')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <span class="mdi mdi-trending-up me-2"></span>
                            Analyse my network
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="workspace-card h-100">
                    @if($hasResult)
                        <h2 class="h3 fw-semibold mb-3">Your network health score</h2>
                        <div class="display-1 fw-bold text-primary">{{ $score }}</div>
                        <p class="text-subtle">Out of 100 · Benchmarked against operators who maintain tight six-degree networks.</p>
                        <div class="workspace-divider"></div>
                        <p class="fw-semibold">{{ $insights['summary'] }}</p>
                        <ul class="mb-0">
                            @foreach($insights['messages'] as $message)
                                <li class="text-subtle">{{ $message }}</li>
                            @endforeach
                        </ul>
                        <div class="workspace-divider"></div>
                        <p class="small text-subtle mb-0">
                            Based on Milgram & Travers’ small-world experiments (1967) and contemporary network science on clustering and tie strength. Increase touchpoints, diversify circles, and shorten response time to move closer to a sub-six-degree network.
                        </p>
                    @else
                        <h2 class="h4 fw-semibold mb-3">What you’ll learn</h2>
                        <ul class="mb-4 text-subtle">
                            <li>How dense your network graph is compared to healthy operator communities.</li>
                            <li>Where you can reduce degrees of separation through introductions and diversity.</li>
                            <li>Why response time and follow-through keep your network resilient.</li>
                        </ul>
                        <div class="alert alert-info mb-0">
                            <span class="mdi mdi-lightbulb-on-outline me-2"></span>
                            Tip: keep your score above 80 to align with the “small-world” benchmarks seen in high-performing cohorts.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

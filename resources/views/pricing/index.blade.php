@extends('layouts.site')

@section('content')
    <section class="site-section text-center">
        <style>
            .workspace-card {
                position: relative;
                background: linear-gradient(180deg, #ffffff 0%, rgba(244,251,255,0.92) 100%);
                border: 1px solid rgba(148,163,184,0.18);
                border-radius: 28px;
                padding: clamp(1.8rem, 4vw, 2.4rem);
                box-shadow: 0 36px 76px -50px rgba(15,23,42,0.18);
            }
        </style>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <span class="site-eyebrow">Plans</span>
        <h1 class="display-4 fw-semibold mt-3">Pick the right operating layer</h1>
        <p class="lead text-subtle mx-auto" style="max-width: 640px;">CoffeeChat OS stays free for emerging operators. Upgrade to unlock unlimited coffee chat flows and priority improvements.</p>
        <div class="row g-4 mt-4 justify-content-center">
            <div class="col-md-5">
                <div class="workspace-card h-100 text-start">
                    <h2 class="h3 fw-semibold">Free</h2>
                    <p class="display-5 fw-bold mb-0">$0<span class="h6 text-subtle"> / month</span></p>
                    <p class="text-subtle">Perfect for starting operators validating their networking rhythm.</p>
                    <ul class="text-start text-subtle">
                        <li>Up to <strong>10 coffee chat</strong> records</li>
                        <li>Team Finder &amp; vacancy-to-coffee-flow workflow</li>
                        <li>Network health analysis</li>
                        <li>MBA Jobs + internship previews</li>
                    </ul>
                    <a href="{{ route('workspace.coffee-chats.index') }}" class="btn btn-outline-primary w-100">Continue for free</a>
                </div>
            </div>
            <div class="col-md-5">
                <div class="workspace-card h-100 text-start border-primary" style="border-width:2px;">
                    <h2 class="h3 fw-semibold">Premium</h2>
                    <p class="display-5 fw-bold mb-0">$10<span class="h6 text-subtle"> / month</span></p>
                    <p class="text-subtle">For connectors orchestrating programs, accelerators, and high-volume networking.</p>
                    <ul class="text-start text-subtle">
                        <li><strong>Unlimited</strong> coffee chat flows</li>
                        <li>Priority roadmap updates &amp; early feature access</li>
                        <li>Advanced analytics (coming soon)</li>
                        <li>Premium support &amp; success desk</li>
                    </ul>
                    @auth
                        @if(auth()->user()->isPremium())
                            <span class="badge bg-success mb-3">You’re on the premium plan</span>
                            <a href="{{ route('workspace.coffee-chats.index') }}" class="btn btn-primary w-100">Open workspace</a>
                        @else
                            @if($stripeConfigured)
                                <form method="POST" action="{{ route('subscription.checkout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Upgrade with Stripe</button>
                                </form>
                                <small class="text-subtle d-block mt-2">Secure checkout via Stripe. Cancel anytime.</small>
                            @else
                                <div class="alert alert-warning">Stripe is not configured yet. Contact the workspace owner.</div>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary w-100">Create account</a>
                        <small class="text-subtle d-block mt-2">Log in to upgrade existing workspaces.</small>
                    @endauth
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-4">
            <span class="mdi mdi-information-outline me-2"></span>
            All plans reference the <strong>small-world network</strong> research—keeping your network within six degrees drives opportunity velocity.
        </div>
    </section>
@endsection

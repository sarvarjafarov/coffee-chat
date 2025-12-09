<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageModel = $page ?? null;
            $seo = $seo ?? [];
            if (empty($seo)) {
                $slug = trim(request()->path(), '/') ?: 'home';
                $seoModel = \App\Models\SeoMeta::forSlug($slug);
                $seo = $seoModel ? $seoModel->toArray() : [];
            }
            $pageTitle = $seo['title'] ?? ($pageModel?->name ?? config('app.name', 'CoffeeChat OS'));
            $pageDescription = $seo['description'] ?? null;
            $pageKeywords = $seo['keywords'] ?? null;
            $canonical = $seo['canonical_url'] ?? null;
            $ogTitle = $seo['og_title'] ?? $pageTitle;
            $ogDescription = $seo['og_description'] ?? $pageDescription;
            $ogImage = $seo['og_image'] ?? null;
            $twitterCard = $seo['twitter_card'] ?? 'summary_large_image';
            $metaTags = collect(data_get($seo, 'meta_tags', []));
            $mediaItems = collect(data_get($seo, 'media', []));
            $schemaEntries = collect(data_get($seo, 'schema', []));
            $advancedMeta = data_get($seo, 'meta');

            if (is_array($advancedMeta)) {
                $metaTags = $metaTags->concat(collect(data_get($advancedMeta, 'meta_tags', [])));
                $schemaEntries = $schemaEntries->concat(
                    collect(data_get($advancedMeta, 'schema', []))->map(function ($entry) {
                        return [
                            'type' => data_get($entry, 'type', 'application/ld+json'),
                            'payload' => data_get($entry, 'payload', $entry),
                        ];
                    })
                );
            }

            $additionalLinks = is_array($advancedMeta)
                ? collect(data_get($advancedMeta, 'links', []))
                : collect();
        @endphp

        <title>{{ $pageTitle }}</title>
        @if($pageDescription)
            <meta name="description" content="{{ $pageDescription }}">
        @endif
        @if($pageKeywords)
            <meta name="keywords" content="{{ $pageKeywords }}">
        @endif
        @if($canonical)
            <link rel="canonical" href="{{ $canonical }}">
        @endif
        <meta property="og:title" content="{{ $ogTitle }}">
        @if($ogDescription)
            <meta property="og:description" content="{{ $ogDescription }}">
        @endif
        <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
        @if($ogImage)
            <meta property="og:image" content="{{ $ogImage }}">
        @endif
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="{{ $twitterCard }}">
        <meta name="twitter:title" content="{{ $ogTitle }}">
        @if($ogDescription)
        <meta name="twitter:description" content="{{ $ogDescription }}">
        @endif
        @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        @include('layouts.partials.analytics')
        @include('components.feedback-widget', ['pageTitle' => $pageTitle ?? null, 'pagePath' => request()->path()])

        @foreach($metaTags as $tag)
            @php
                $tagContent = data_get($tag, 'content');
                if (! filled($tagContent)) {
                    continue;
                }
                $tagName = data_get($tag, 'name');
                $tagProperty = data_get($tag, 'property');
                $tagHttpEquiv = data_get($tag, 'http_equiv');
            @endphp
            <meta
                @if($tagName) name="{{ $tagName }}" @endif
                @if($tagProperty) property="{{ $tagProperty }}" @endif
                @if($tagHttpEquiv) http-equiv="{{ $tagHttpEquiv }}" @endif
                content="{{ $tagContent }}"
            >
        @endforeach

        @foreach($mediaItems as $media)
            @php
                $mediaUrl = trim((string) data_get($media, 'url'));
                if ($mediaUrl === '') {
                    continue;
                }
                $mediaType = data_get($media, 'type', 'open_graph');
                $mediaAlt = data_get($media, 'alt');
                $mediaMime = data_get($media, 'mime_type');
                $mediaWidth = data_get($media, 'width');
                $mediaHeight = data_get($media, 'height');
                $mediaSizes = data_get($media, 'sizes');
            @endphp
            @switch($mediaType)
                @case('twitter')
                    <meta name="twitter:image" content="{{ $mediaUrl }}">
                    @if($mediaAlt)
                        <meta name="twitter:image:alt" content="{{ $mediaAlt }}">
                    @endif
                    @break
                @case('icon')
                    <link rel="icon" href="{{ $mediaUrl }}" @if($mediaMime) type="{{ $mediaMime }}" @endif @if($mediaSizes) sizes="{{ $mediaSizes }}" @endif>
                    @break
                @case('apple_touch_icon')
                    <link rel="apple-touch-icon" href="{{ $mediaUrl }}" @if($mediaSizes) sizes="{{ $mediaSizes }}" @endif>
                    @break
                @default
                    <meta property="og:image" content="{{ $mediaUrl }}">
                    @if($mediaAlt)
                        <meta property="og:image:alt" content="{{ $mediaAlt }}">
                    @endif
                    @if($mediaMime)
                        <meta property="og:image:type" content="{{ $mediaMime }}">
                    @endif
                    @if($mediaWidth)
                        <meta property="og:image:width" content="{{ $mediaWidth }}">
                    @endif
                    @if($mediaHeight)
                        <meta property="og:image:height" content="{{ $mediaHeight }}">
                    @endif
            @endswitch
        @endforeach

        @foreach($additionalLinks as $link)
            @php
                $rel = data_get($link, 'rel');
                $href = data_get($link, 'href');
                if (! $rel || ! $href) {
                    continue;
                }
                $hreflang = data_get($link, 'hreflang');
                $linkType = data_get($link, 'type');
                $media = data_get($link, 'media');
            @endphp
            <link rel="{{ $rel }}" href="{{ $href }}" @if($hreflang) hreflang="{{ $hreflang }}" @endif @if($linkType) type="{{ $linkType }}" @endif @if($media) media="{{ $media }}" @endif>
        @endforeach

        @foreach($schemaEntries as $schemaEntry)
            @php
                $schemaPayload = data_get($schemaEntry, 'payload', $schemaEntry);
                if (! is_array($schemaPayload)) {
                    continue;
                }
                $schemaType = data_get($schemaEntry, 'type', 'application/ld+json');
                $schemaJson = json_encode($schemaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            @endphp
            @if($schemaJson)
                <script type="{{ $schemaType }}">{!! $schemaJson !!}</script>
            @endif
        @endforeach

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('coffeechat-os-favicon.png?v=3') }}">
        <link rel="icon" type="image/svg+xml" sizes="any" href="{{ asset('favicon.svg?v=3') }}">
        <link rel="shortcut icon" href="{{ asset('coffeechat-os-favicon.png?v=3') }}">
        <link rel="apple-touch-icon" href="{{ asset('coffeechat-os-favicon.png?v=3') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
        @php
            $themeSettings = isset($siteSettings) ? collect($siteSettings) : collect();
            $themeAccentStart = data_get($themeSettings, 'accent_start', '#0ea5e9');
            $themeAccentEnd = data_get($themeSettings, 'accent_end', '#2563eb');
            $themeSurface = data_get($themeSettings, 'surface', '#f4fbff');
            $themeSurfaceAlt = data_get($themeSettings, 'surface_alt', '#e6f6ff');
            $themeTextPrimary = data_get($themeSettings, 'text_primary', '#0f172a');
            $themeTextMuted = data_get($themeSettings, 'text_muted', '#475569');
        @endphp
        <style>
            :root {
                --surface: {{ $themeSurface }};
                --surface-alt: {{ $themeSurfaceAlt }};
                --surface-card: #ffffff;
                --text-primary: {{ $themeTextPrimary }};
                --text-muted: {{ $themeTextMuted }};
                --accent: {{ $themeAccentStart }};
                --accent-strong: {{ $themeAccentEnd }};
                --accent-soft: #d6f1ff;
                --border-soft: #cfe5f2;
            }

            body {
                font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: linear-gradient(180deg, var(--surface) 0%, #ffffff 65%, #f5fbff 100%);
                color: var(--text-primary);
                min-height: 100vh;
            }

            a {
                color: var(--accent);
            }

            a:hover {
                color: var(--accent-strong);
            }

            .navbar-wrap {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(18px);
                border-bottom: 1px solid rgba(15, 23, 42, 0.05);
                box-shadow: 0 12px 24px -16px rgba(15, 23, 42, 0.18);
                padding-top: 0.85rem;
                padding-top: calc(0.85rem + env(safe-area-inset-top));
                padding-bottom: 0.85rem;
            }

            .navbar {
                border-radius: 1.5rem;
                padding: 0.85rem 1.8rem;
                border: 1px solid rgba(15, 23, 42, 0.05);
                background: rgba(255, 255, 255, 0.96);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.05rem;
                letter-spacing: 0.03em;
                color: var(--text-primary) !important;
            }

            .navbar-toggler {
                border: 1px solid rgba(15, 23, 42, 0.15);
                border-radius: 14px;
                padding: 0.4rem 0.55rem;
                background-color: rgba(255,255,255,0.96);
                transition: box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .navbar-toggler:focus {
                border-color: rgba(59,130,246,0.45);
                box-shadow: 0 0 0 0.15rem rgba(59,130,246,0.2);
            }

            .navbar-toggler-icon {
                width: 1.4rem;
                height: 1.4rem;
                background-size: 100% 100%;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(29,78,216,0.9)' stroke-width='2.4' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }

            .nav-link {
                font-weight: 500;
                color: var(--text-muted) !important;
                border-radius: 999px;
                padding: 0.45rem 1rem !important;
                transition: all 0.2s ease-out;
            }

            .nav-link.active,
            .nav-link:hover {
                color: var(--text-primary) !important;
                background: var(--accent-soft);
                box-shadow: 0 6px 16px -12px rgba(2, 132, 199, 0.65);
            }

            main {
                padding-top: 4rem;
                padding-bottom: 5rem;
            }

            .cta-btn {
                background: linear-gradient(120deg, var(--accent) 0%, var(--accent-strong) 100%);
                color: #fff !important;
                border: none;
                border-radius: 999px;
                padding: 0.58rem 1.65rem;
                font-weight: 600;
                box-shadow: 0 14px 24px -16px rgba(2, 132, 199, 0.65);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .cta-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 18px 28px -18px rgba(2, 132, 199, 0.75);
            }

            .text-subtle {
                color: var(--text-muted);
            }

            .site-section {
                width: clamp(320px, 92vw, 1200px);
                margin: 0 auto;
                padding: clamp(2.5rem, 5vw, 3.6rem) clamp(1.5rem, 5vw, 3.5rem);
                background: rgba(255,255,255,0.78);
                border-radius: 32px;
                border: 1px solid rgba(148,163,184,0.14);
                box-shadow: 0 40px 80px -60px rgba(15,23,42,0.18);
            }

            .site-section + .site-section {
                margin-top: clamp(2rem, 4vw, 3.2rem);
            }

            .site-section--transparent {
                background: transparent;
                border: none;
                box-shadow: none;
            }

            .site-section-header {
                margin-bottom: clamp(1.5rem, 3vw, 2.5rem);
            }

            .site-eyebrow {
                text-transform: uppercase;
                letter-spacing: 0.18em;
                font-size: 0.78rem;
                color: rgba(100,116,139,0.7);
            }

            .site-link {
                font-weight: 600;
                color: var(--accent-strong);
                text-decoration: none;
            }

            .site-link:hover {
                color: var(--accent);
            }

            .feature-card {
                background: linear-gradient(180deg, #ffffff 0%, rgba(244,251,255,0.9) 100%);
                border: 1px solid rgba(148,163,184,0.14);
                border-radius: 24px;
                padding: clamp(1.8rem, 4vw, 2.4rem);
                height: 100%;
                box-shadow: 0 30px 60px -50px rgba(15,23,42,0.18);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .feature-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 34px 70px -48px rgba(15,23,42,0.22);
            }

            .site-footer {
                position: relative;
                background: linear-gradient(180deg, rgba(8,47,73,0.96) 0%, rgba(15,23,42,0.88) 100%);
                color: rgba(226,232,240,0.95);
                border: none;
                margin-top: clamp(4rem, 8vw, 6rem);
                overflow: hidden;
                padding: clamp(3rem, 6vw, 4.5rem) 0;
            }

            .site-footer::before {
                content: "";
                position: absolute;
                inset: -20% -40%;
                background: radial-gradient(55% 65% at 15% 15%, rgba(56,189,248,0.28), transparent 75%),
                    radial-gradient(55% 65% at 85% 10%, rgba(14,165,233,0.2), transparent 70%),
                    repeating-linear-gradient(125deg, rgba(148,163,184,0.1) 0, rgba(148,163,184,0.1) 4px, transparent 4px, transparent 20px);
                opacity: 0.65;
                pointer-events: none;
            }

            .site-footer::after {
                content: "";
                position: absolute;
                inset: 0;
                background: radial-gradient(90% 140% at 50% -10%, rgba(255,255,255,0.18), transparent 65%);
                pointer-events: none;
            }

            .footer-glass {
                position: relative;
                z-index: 1;
                background: rgba(15,23,42,0.4);
                border: 1px solid rgba(148,163,184,0.2);
                border-radius: 32px;
                padding: clamp(2.6rem, 5vw, 3.4rem);
                box-shadow: 0 40px 120px -50px rgba(8,47,73,0.65);
                backdrop-filter: blur(16px);
            }

            .footer-top {
                display: flex;
                flex-direction: column;
                gap: clamp(1.8rem, 4vw, 2.6rem);
            }

            @media (min-width: 992px) {
                .footer-top {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }
            }

            .footer-brand {
                display: flex;
                align-items: center;
                gap: 1.2rem;
                color: rgba(248,250,252,0.98);
            }

            .footer-logo {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                display: grid;
                place-items: center;
                background: linear-gradient(160deg, rgba(59,130,246,0.9), rgba(14,165,233,0.85));
                box-shadow: 0 28px 48px -22px rgba(14,165,233,0.6);
            }

            .footer-logo .mdi {
                font-size: 1.9rem;
                color: #fff;
            }

            .footer-name {
                font-size: 1.25rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .footer-tagline {
                margin: 0.35rem 0 0;
                color: rgba(226,232,240,0.75);
                font-size: 0.95rem;
            }

            .footer-social {
                display: flex;
                align-items: center;
                gap: 0.9rem;
                flex-wrap: wrap;
            }

            .footer-social a {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                display: grid;
                place-items: center;
                background: rgba(15,23,42,0.55);
                color: rgba(226,232,240,0.9);
                border: 1px solid rgba(148,163,184,0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .footer-social a:hover {
                transform: translateY(-3px);
                border-color: rgba(56,189,248,0.6);
                box-shadow: 0 20px 40px -25px rgba(14,165,233,0.65);
                color: #fff;
            }

            .footer-divider {
                margin: clamp(2rem, 4vw, 2.8rem) 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(148,163,184,0.35), transparent);
            }

            .footer-bottom {
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
            }

            @media (min-width: 992px) {
                .footer-bottom {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem 1.6rem;
                color: rgba(226,232,240,0.78);
            }

            .footer-links a {
                color: inherit;
                text-decoration: none;
                font-weight: 500;
            }

            .footer-links a:hover {
                color: #fff;
            }

            .footer-copy {
                margin: 0;
                color: rgba(226,232,240,0.65);
                font-size: 0.92rem;
            }

            @media (max-width: 768px) {
                .navbar-wrap {
                    padding-top: 0.65rem;
                    padding-top: calc(0.65rem + env(safe-area-inset-top));
                    padding-bottom: 0.65rem;
                }

                .navbar {
                    border-radius: 1.25rem;
                    padding: 0.75rem 1.2rem;
                }

                main {
                    padding-top: 3.2rem;
                    padding-bottom: 4rem;
                }

                .site-section {
                    width: min(100%, 680px);
                    padding: clamp(1.8rem, 6vw, 2.4rem) clamp(1rem, 6vw, 1.6rem);
                    border-radius: 28px;
                }

                .site-section + .site-section {
                    margin-top: clamp(1.4rem, 6vw, 2.2rem);
                }

                .footer-glass {
                    padding: clamp(2rem, 7vw, 2.6rem);
                    border-radius: 26px;
                }

                .footer-top {
                    gap: 1.8rem;
                    align-items: flex-start;
                }

                .footer-brand {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.9rem;
                }

                .footer-social {
                    justify-content: flex-start;
                }

                .footer-bottom {
                    align-items: flex-start;
                    gap: 1.6rem;
                }

                .footer-links {
                    flex-direction: column;
                    gap: 0.75rem;
                }
            }
        </style>
    </head>
    <body>
<div class="navbar-wrap py-3">
    <nav class="navbar navbar-expand-lg container-xxl px-3 px-lg-4">
        <div class="container-fluid px-0">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <span class="mdi mdi-coffee-outline me-2 fs-3 text-primary"></span>
                <span>{{ config('app.name', 'CoffeeChat OS') }}</span>
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mainNav">
                @php($menuItems = \App\Models\SiteMenuItem::where('is_visible', true)->orderBy('sort_order')->orderBy('label')->get())
                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    @forelse($menuItems as $item)
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() === url($item->url) ? 'active' : '' }}" href="{{ url($item->url) }}">{{ $item->label }}</a>
                        </li>
                    @empty
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Platform</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('stories') ? 'active' : '' }}" href="{{ route('stories') }}">Solutions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('insights') ? 'active' : '' }}" href="{{ route('insights') }}">Insights</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('network-health*') ? 'active' : '' }}" href="{{ route('network-health') }}">Network health</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mba.jobs') ? 'active' : '' }}" href="{{ route('mba.jobs') }}">MBA Full-time Jobs</a>
                        </li>
                    @endforelse
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('workspace.*') ? 'active' : '' }}" href="{{ route('workspace.coffee-chats.index') }}">Workspace</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link px-3">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link cta-btn ms-lg-3" href="{{ route('register') }}">Start free trial</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</div>

<main class="py-5">
    <div class="w-100">
        @yield('content')
    </div>
</main>

<footer class="site-footer mt-auto">
    <div class="container-xxl">
        <div class="footer-glass">
            <div class="footer-top">
                <div class="footer-brand">
                    <span class="footer-logo">
                        <span class="mdi mdi-coffee-outline"></span>
                    </span>
                    <div>
                        <span class="footer-name">{{ config('app.name', 'CoffeeChat OS') }}</span>
                        <p class="footer-tagline">Craft orchestrated relationships with AI copilots.</p>
                    </div>
                </div>
                <div class="footer-social">
                    <a href="https://www.linkedin.com" aria-label="LinkedIn" target="_blank" rel="noopener">
                        <span class="mdi mdi-linkedin"></span>
                    </a>
                    <a href="https://twitter.com" aria-label="Twitter" target="_blank" rel="noopener">
                        <span class="mdi mdi-twitter"></span>
                    </a>
                    <a href="https://www.youtube.com" aria-label="YouTube" target="_blank" rel="noopener">
                        <span class="mdi mdi-youtube"></span>
                    </a>
                    <a href="mailto:hello@coffeechat.os" aria-label="Email">
                        <span class="mdi mdi-email-outline"></span>
                    </a>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-bottom">
                <p class="footer-copy mb-0">© {{ now()->year }} CoffeeChat OS. Crafted for ambitious networkers worldwide.</p>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Platform</a>
                    <a href="{{ route('stories') }}">Customer stories</a>
                    <a href="{{ route('insights') }}">Insights</a>
                    <a href="mailto:hello@coffeechat.os">Contact</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

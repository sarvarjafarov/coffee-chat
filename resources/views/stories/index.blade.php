@extends('layouts.site')

@section('content')
    @php
        $hero = $components['hero'] ?? null;
        $meta = $hero?->meta ?? [];
        $cta = data_get($meta, 'cta');
        $stat = data_get($meta, 'stat');
    @endphp

    <style>
        .stories-hero {
            position: relative;
            background:
                radial-gradient(120% 150% at 12% -15%, rgba(56,189,248,0.24), transparent 65%),
                radial-gradient(120% 150% at 90% -20%, rgba(14,165,233,0.2), transparent 70%),
                linear-gradient(180deg, rgba(244,251,255,0.96) 0%, rgba(233,244,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 48px 110px -60px rgba(15,23,42,0.28);
            overflow: hidden;
        }

        .stories-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 22px);
            opacity: 0.5;
            pointer-events: none;
        }

        .stories-hero-inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            align-items: center;
            gap: clamp(2rem, 4vw, 3rem);
        }

        .stories-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.15);
            color: rgba(14,165,233,0.85);
            font-weight: 700;
            font-size: 0.74rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .stories-hero h1 {
            font-size: clamp(2.4rem, 3.2vw + 1rem, 3.6rem);
            font-weight: 700;
            line-height: 1.05;
        }

        .stories-hero-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stories-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.8rem 1.9rem;
            font-weight: 600;
            background: linear-gradient(125deg, rgba(14,165,233,0.95), rgba(37,99,235,0.85));
            color: #fff;
            border: none;
            box-shadow: 0 24px 48px -28px rgba(15,23,42,0.25);
        }

        .stories-cta:hover {
            color: #fff;
            transform: translateY(-2px);
        }

        .stories-stat {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1.1rem;
            background: rgba(14,165,233,0.1);
            border-radius: 999px;
            color: rgba(15,23,42,0.85);
            font-weight: 600;
        }

        .stories-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: clamp(1.6rem, 3vw, 2.2rem);
            padding: 0 clamp(0.75rem, 3vw, 2.5rem);
        }

        @media (max-width: 1199px) {
            .stories-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .stories-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .story-card {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.92) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 28px;
            padding: clamp(1.9rem, 4vw, 2.5rem);
            box-shadow: 0 32px 70px -48px rgba(15,23,42,0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .story-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 80px -52px rgba(15,23,42,0.24);
        }

        .story-card h2 a {
            color: var(--text-primary);
            text-decoration: none;
        }

        .story-card h2 a:hover {
            color: var(--accent-strong);
        }

        .story-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(71,85,105,0.7);
        }

        .stories-pagination {
            margin-top: clamp(2rem, 4vw, 3rem);
        }
    </style>

    <section class="site-section stories-hero mb-5">
        <div class="stories-hero-inner">
            <div class="stories-hero-copy">
                <span class="stories-hero-badge"><span class="mdi mdi-book-open-variant"></span> Stories</span>
                <h1 class="mt-3 mb-3">{{ $hero->title ?? 'Stories & Playbooks' }}</h1>
                <p class="lead text-subtle mb-0">{{ $hero->subtitle ?? 'Deep dives, frameworks, and interview scripts from community members who turned conversations into offers and partnerships.' }}</p>
            </div>
            <div class="stories-hero-meta">
                @if($cta)
                    <a href="{{ url(data_get($cta, 'url', '#')) }}" class="stories-cta">
                        <span class="mdi mdi-pencil-plus-outline"></span>
                        {{ data_get($cta, 'label', 'Share your story') }}
                    </a>
                @endif
                @if($stat)
                    <span class="stories-stat">
                        <span class="mdi mdi-chart-line"></span>
                        {{ $stat }}
                    </span>
                @endif
            </div>
        </div>
    </section>

    <section class="site-section">
        <div class="stories-grid">
            @forelse($posts as $post)
                <article class="story-card">
                    <div class="story-meta">
                        <span>{{ $post->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                        <span>{{ strtoupper($post->author?->name ?? 'CoffeeChat OS') }}</span>
                    </div>
                    <h2 class="h4 fw-semibold mb-1">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="text-subtle mb-0 flex-grow-1">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 160) }}</p>
                    <div class="d-flex align-items-center gap-2 text-subtle small mt-auto">
                        <span class="mdi mdi-timer-sand-empty"></span>
                        {{ max(1, ceil(str_word_count(strip_tags($post->body)) / 180)) }} min read
                    </div>
                </article>
            @empty
                <div class="alert alert-info mb-0">No stories published yet. Publish a post from the admin dashboard.</div>
            @endforelse
        </div>

        <div class="stories-pagination d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </section>
@endsection

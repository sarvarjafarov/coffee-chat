@extends('layouts.site')

@section('content')
    <style>
        .post-card {
            background: linear-gradient(180deg, #ffffff 0%, rgba(244,251,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 28px;
            padding: clamp(1.8rem, 4vw, 2.4rem);
            height: 100%;
            box-shadow: 0 28px 60px -46px rgba(15,23,42,0.18);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .post-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 36px 72px -50px rgba(15,23,42,0.22);
        }
    </style>

    <section class="site-section">
        <header class="site-section-header text-center mb-4">
            <p class="site-eyebrow mb-2">Field notes</p>
            <h1 class="display-5 fw-semibold mb-2">Latest frameworks and playbooks</h1>
            <p class="text-subtle mb-0">Observations from the CoffeeChat OS community to help you orchestrate every conversation with precision.</p>
        </header>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-xl-4">
                    <article class="post-card">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary-subtle text-primary-emphasis">{{ $post->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                                <span class="text-subtle small">{{ strtoupper($post->author?->name ?? 'CoffeeChat OS') }}</span>
                            </div>
                            <h2 class="h4 fw-semibold mb-3"><a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">{{ $post->title }}</a></h2>
                            <p class="text-subtle flex-grow-1 mb-4">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 160) }}</p>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="site-link d-inline-flex align-items-center gap-1">Read insight <span class="mdi mdi-arrow-right"></span></a>
                    </article>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info">No posts have been published yet. Check back soon!</div>
                </div>
            @endforelse
        </div>
    </section>

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
@endsection

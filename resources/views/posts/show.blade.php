@extends('layouts.site')

@section('content')
    <style>
        .post-show {
            max-width: 780px;
            margin: 0 auto;
        }
        .post-excerpt {
            background: rgba(244,251,255,0.9);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 24px;
            box-shadow: 0 28px 60px -46px rgba(15,23,42,0.18);
        }
        .post-body {
            line-height: 1.75;
            color: rgba(51,65,85,0.92);
        }
    </style>

    <article class="site-section post-show">
        <header class="mb-5">
            <p class="text-subtle text-uppercase small mb-2">Field Note</p>
            <h1 class="display-4 fw-semibold mb-3">{{ $post->title }}</h1>
            <p class="text-subtle mb-0">
                @if($post->published_at)
                    Published {{ $post->published_at->format('F d, Y') }}
                @endif
                @if($post->author)
                    · {{ $post->author->name }}
                @endif
            </p>
        </header>

        @if($post->excerpt)
            <div class="post-excerpt p-4 mb-4">
                <p class="lead text-subtle mb-0">{{ $post->excerpt }}</p>
            </div>
        @endif

        <div class="fs-5 post-body">
            {!! nl2br(e($post->body)) !!}
        </div>
    </article>

    <div class="mt-5">
        <a href="{{ url()->previous() ?? route('stories') }}" class="btn btn-outline-primary">← Back</a>
    </div>
@endsection

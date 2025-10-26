@extends('layouts.admin')

@section('title', $post->title)

@section('actions')
    <div class="btn-group float-sm-right" role="group">
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-1">
                <strong>Status:</strong>
                {{ $post->is_published ? 'Published' : 'Draft' }}
                @if($post->is_published && $post->published_at)
                    on {{ $post->published_at->format('M d, Y H:i') }}
                @endif
            </p>
            <p class="text-muted mb-4">
                <strong>Author:</strong> {{ $post->author?->name ?? '—' }}
            </p>
            @if($post->excerpt)
                <p class="lead">{{ $post->excerpt }}</p>
            @endif
            <div class="content">
                {!! nl2br(e($post->body)) !!}
            </div>
        </div>
    </div>
@endsection

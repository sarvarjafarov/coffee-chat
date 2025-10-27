@extends('layouts.admin')

@section('title', 'SEO Manager')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-primary mr-2">Manage Pages</a>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary">Manage Posts</a>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-0 text-muted">Configure meta tags, share images, and structured data for each published page or post. The site layout will automatically render these settings using the SEO package.</p>
        </div>
    </div>

    @foreach($resources as $group)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $group['label'] }}</h5>
                <span class="badge badge-light text-uppercase">{{ $group['items']->count() }} {{ \Illuminate\Support\Str::plural('record', $group['items']->count()) }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Current title</th>
                        <th class="d-none d-lg-table-cell">Updated</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($group['items'] as $item)
                        <tr>
                            <td>
                                @if($group['type'] === 'pages')
                                    <strong>{{ $item->name }}</strong>
                                    <div class="text-muted small">/{{ $item->slug }}</div>
                                @else
                                    <strong>{{ $item->title }}</strong>
                                    <div class="text-muted small">{{ optional($item->published_at)->format('M j, Y') ?? 'Draft' }}</div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell text-muted">
                                {{ $item->seo?->title ?? '—' }}
                            </td>
                            <td class="d-none d-lg-table-cell text-muted">
                                {{ optional($item->seo?->updated_at)->diffForHumans() ?? 'Never' }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.seo.edit', ['type' => $group['type'], 'id' => $item->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-sliders-h mr-1"></i> Edit SEO
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No {{ $group['label'] }} found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($orphanedSeo->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detached SEO records</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th class="d-none d-md-table-cell">Title</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orphanedSeo as $record)
                        <tr>
                            <td>#{{ $record->id }}</td>
                            <td class="d-none d-md-table-cell">{{ $record->title ?? '—' }}</td>
                            <td>{{ optional($record->updated_at)->diffForHumans() ?? 'Unknown' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted small">
                These records no longer have a linked model. You can safely delete them from the database if they are not needed.
            </div>
        </div>
    @endif
@endsection

@extends('layouts.admin')

@section('title', 'Components — ' . $page->name)

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.pages.components.create', $page) }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> New Component
        </a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title mb-0">Page summary</h5>
            <p class="text-muted mb-0">Slug: <code>{{ $page->slug }}</code> · {{ $page->description ?? 'No description' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Key</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Position</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($components as $component)
                    <tr>
                        <td><code>{{ $component->key }}</code></td>
                        <td>{{ $component->title ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($component->subtitle, 60) }}</td>
                        <td>{{ $component->position }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.pages.components.edit', [$page, $component]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.pages.components.destroy', [$page, $component]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this component?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No components configured.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

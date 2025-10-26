@extends('layouts.admin')

@section('title', 'SEO Manager')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.seo.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> New SEO Entry
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Slug</th>
                    <th>Page</th>
                    <th>Title</th>
                    <th>Canonical</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($seoEntries as $entry)
                    <tr>
                        <td><code>{{ $entry->slug }}</code></td>
                        <td>{{ $entry->page?->name ?? '—' }}</td>
                        <td>{{ $entry->title ?? '—' }}</td>
                        <td class="text-truncate" style="max-width: 240px;">{{ $entry->canonical_url ?? '—' }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.seo.edit', $entry) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.seo.destroy', $entry) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this SEO entry?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No SEO entries defined yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

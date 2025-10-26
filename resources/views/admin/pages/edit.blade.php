@extends('layouts.admin')

@section('title', 'Edit Page')

@section('actions')
    <div class="btn-group float-sm-right" role="group">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('admin.pages.components.index', $page) }}" class="btn btn-outline-primary">
            <i class="fas fa-th-large"></i> Manage components
        </a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.pages._form')
                <button class="btn btn-primary">Update Page</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Components
        </div>
        <div class="card-body">
            @if($page->components->isEmpty())
                <p class="text-muted mb-0">No components yet. <a href="{{ route('admin.pages.components.create', $page) }}">Create one</a> to start composing this page.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($page->components as $component)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $component->key }}</strong>
                                <span class="text-muted">{{ $component->title ?? '' }}</span>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.pages.components.edit', [$page, $component]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.pages.components.destroy', [$page, $component]) }}" method="POST" onsubmit="return confirm('Delete this component?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

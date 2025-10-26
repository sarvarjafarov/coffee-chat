@extends('layouts.admin')

@section('title', 'Edit Component — ' . $component->key)

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.pages.components.index', $page) }}" class="btn btn-secondary">Back</a>
        <form action="{{ route('admin.pages.components.destroy', [$page, $component]) }}" method="POST" onsubmit="return confirm('Delete this component?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pages.components.update', [$page, $component]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.pages.components._form')
                <button class="btn btn-primary">Update Component</button>
            </form>
        </div>
    </div>
@endsection

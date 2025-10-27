@extends('layouts.admin')

@section('title', 'Edit SEO — ' . ($type === 'pages' ? $resource->name : $resource->title))

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">Back to SEO manager</a>
        @if($type === 'pages')
            <a href="{{ route('admin.pages.edit', $resource) }}" class="btn btn-outline-primary">Edit page</a>
        @else
            <a href="{{ route('admin.posts.edit', $resource) }}" class="btn btn-outline-primary">Edit post</a>
        @endif
    </div>
@endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">SEO settings</h5>
            <span class="badge badge-light text-uppercase">{{ ucfirst(\Illuminate\Support\Str::singular($type)) }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.seo.update', ['type' => $type, 'id' => $resource->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.seo._form', ['seo' => $seo])
                <button class="btn btn-primary">Save SEO</button>
            </form>
        </div>
    </div>
@endsection

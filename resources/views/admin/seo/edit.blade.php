@extends('layouts.admin')

@section('title', 'Edit SEO Entry')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">Back</a>
        <form action="{{ route('admin.seo.destroy', $seoMeta) }}" method="POST" onsubmit="return confirm('Delete this SEO entry?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.seo.update', $seoMeta) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.seo._form')
                <button class="btn btn-primary">Update SEO</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Create Post')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back to Posts</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.posts.store') }}" method="POST">
                @csrf
                @include('admin.posts._form')
                <button type="submit" class="btn btn-primary">Save Post</button>
            </form>
        </div>
    </div>
@endsection

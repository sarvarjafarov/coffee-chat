@extends('layouts.admin')

@section('title', 'Edit Post')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back to Posts</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.posts._form', ['post' => $post])
                <button type="submit" class="btn btn-primary">Update Post</button>
            </form>
        </div>
    </div>
@endsection

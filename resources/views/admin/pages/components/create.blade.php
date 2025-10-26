@extends('layouts.admin')

@section('title', 'Add Component — ' . $page->name)

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.pages.components.index', $page) }}" class="btn btn-secondary">Back to components</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pages.components.store', $page) }}" method="POST">
                @csrf
                @include('admin.pages.components._form')
                <button class="btn btn-primary">Create Component</button>
            </form>
        </div>
    </div>
@endsection

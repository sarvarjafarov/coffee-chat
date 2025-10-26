@extends('layouts.admin')

@section('title', 'Create Page')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf
                @include('admin.pages._form')
                <button class="btn btn-primary">Create Page</button>
            </form>
        </div>
    </div>
@endsection

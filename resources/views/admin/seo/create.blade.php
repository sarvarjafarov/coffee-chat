@extends('layouts.admin')

@section('title', 'Create SEO Entry')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.seo.store') }}" method="POST">
                @csrf
                @include('admin.seo._form')
                <button class="btn btn-primary">Save SEO</button>
            </form>
        </div>
    </div>
@endsection

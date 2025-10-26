@extends('layouts.admin')

@section('title', 'Add Channel')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.channels.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.channels.store') }}" method="POST">
                @csrf
                @include('admin.channels._form', ['channel' => new \App\Models\Channel()])
                <button class="btn btn-primary">Save Channel</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Channel')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.channels.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.channels.update', $channel) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.channels._form')
                <button class="btn btn-primary">Update Channel</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Usage</div>
        <div class="card-body">
            <p class="mb-1 text-muted">Coffee Chats using this channel</p>
            <p>{{ $channel->coffee_chats_count }}</p>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.channels.destroy', $channel) }}" method="POST" onsubmit="return confirm('Delete this channel? Existing chats will lose this tag.');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete Channel</button>
            </form>
        </div>
    </div>
@endsection

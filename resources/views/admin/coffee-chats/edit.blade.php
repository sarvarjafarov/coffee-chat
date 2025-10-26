@extends('layouts.admin')

@section('title', 'Edit Coffee Chat')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.coffee-chats.show', $coffeeChat) }}" class="btn btn-outline-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('admin.coffee-chats.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.coffee-chats.update', $coffeeChat) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.coffee-chats._form')
                <button type="submit" class="btn btn-primary">Update Coffee Chat</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Danger Zone
        </div>
        <div class="card-body">
            <form action="{{ route('admin.coffee-chats.destroy', $coffeeChat) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coffee chat?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete Coffee Chat</button>
            </form>
        </div>
    </div>
@endsection

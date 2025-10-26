@extends('layouts.admin')

@section('title', 'Log Coffee Chat')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.coffee-chats.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coffee-chats.store') }}" method="POST">
                @csrf
                @include('admin.coffee-chats._form')
                <button type="submit" class="btn btn-primary">Save Coffee Chat</button>
            </form>
        </div>
    </div>
@endsection

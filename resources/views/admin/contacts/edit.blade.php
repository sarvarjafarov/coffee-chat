@extends('layouts.admin')

@section('title', 'Edit Contact')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.contacts.update', $contact) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.contacts._form')
                <button class="btn btn-primary">Update Contact</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Recent Coffee Chats</div>
        <div class="card-body">
            @if($contact->coffeeChats->isEmpty())
                <p class="text-muted mb-0">No coffee chats logged with this contact yet.</p>
            @else
                <ul class="list-unstyled mb-0">
                    @foreach($contact->coffeeChats->take(5) as $chat)
                        <li class="mb-2">
                            <a href="{{ route('admin.coffee-chats.show', $chat) }}">
                                {{ $chat->position_title ?? 'Coffee chat' }}
                            </a>
                            <span class="text-muted small">
                                — {{ $chat->scheduled_at?->format('M d, Y') ?? 'No date' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this contact?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete Contact</button>
            </form>
        </div>
    </div>
@endsection

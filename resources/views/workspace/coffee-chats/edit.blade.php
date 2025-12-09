@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Update entry</span>
            <h1>Edit coffee chat</h1>
            <p class="text-subtle mb-0">Refresh notes, status, or follow-up actions for this conversation.</p>
        </div>
    </div>

    <div class="workspace-card workspace-section workspace-form">
        @if(session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('workspace.coffee-chats.update', $chat) }}"
            data-analytics-event="coffee_chat_submit"
            data-context="update"
            data-chat-id="{{ $chat->id }}"
            data-analytics-fields="status,scheduled_at,position_title"
        >
            @csrf
            @method('PUT')
            @include('workspace.coffee-chats._form')
            <div class="workspace-divider"></div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('workspace.coffee-chats.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary">Update coffee chat</button>
            </div>
        </form>
    </div>
@endsection

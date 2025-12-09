@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Create entry</span>
            <h1>Log coffee chat</h1>
            <p class="text-subtle mb-0">Capture meeting details, notes, and follow-up tasks in one place.</p>
        </div>
    </div>

    <div class="workspace-card workspace-section workspace-form">
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
            action="{{ route('workspace.coffee-chats.store') }}"
            data-analytics-event="coffee_chat_submit"
            data-context="create"
            data-analytics-fields="status,scheduled_at,position_title"
        >
            @csrf
            @include('workspace.coffee-chats._form')
            <div class="workspace-divider"></div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('workspace.coffee-chats.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary">Save coffee chat</button>
            </div>
        </form>
    </div>
@endsection

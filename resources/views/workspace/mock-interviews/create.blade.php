@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Mock interview scheduler</span>
            <h1>Schedule a mock interview</h1>
            <p class="text-subtle mb-0">Add the external join link, reminders, and agenda so partners stay aligned.</p>
        </div>
        <a href="{{ route('workspace.mock-interviews.index') }}" class="btn btn-outline-secondary">
            <span class="mdi mdi-arrow-left"></span>
            Back to list
        </a>
    </div>

    <div class="workspace-card workspace-section">
        <form method="POST" action="{{ route('workspace.mock-interviews.store') }}" class="workspace-form">
            @csrf
            @include('workspace.mock-interviews._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('workspace.mock-interviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary">Save mock</button>
            </div>
        </form>
    </div>
@endsection

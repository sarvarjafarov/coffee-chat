@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Case practice</span>
            <h1>Log a case session</h1>
            <p class="text-subtle mb-0">Pick a case, set timing, and capture reflection with an opt-in for LLM feedback.</p>
        </div>
        <a href="{{ route('workspace.cases.index') }}" class="btn btn-outline-secondary">
            <span class="mdi mdi-arrow-left"></span>
            Back to list
        </a>
    </div>

    <div class="workspace-card workspace-section">
        <form method="POST" action="{{ route('workspace.cases.store') }}" class="workspace-form">
            @csrf
            @include('workspace.cases._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('workspace.cases.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary">Save case</button>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Company')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.companies.update', $company) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.companies._form')
                <button class="btn btn-primary">Update Company</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Details</div>
        <div class="card-body">
            <p class="mb-1 text-muted">Total Coffee Chats</p>
            <p>{{ $company->coffee_chats_count }}</p>
            <p class="mb-1 text-muted">Contacts</p>
            <p>{{ $company->contacts_count }}</p>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? Associated contacts will become uncategorized.');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete Company</button>
            </form>
        </div>
    </div>
@endsection

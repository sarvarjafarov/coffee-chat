@extends('layouts.admin')

@section('title', 'Add Contact')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.contacts.store') }}" method="POST">
                @csrf
                @include('admin.contacts._form', ['contact' => new \App\Models\Contact()])
                <button class="btn btn-primary">Save Contact</button>
            </form>
        </div>
    </div>
@endsection

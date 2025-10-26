@extends('layouts.admin')

@section('title', 'Add Company')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.companies.store') }}" method="POST">
                @csrf
                @include('admin.companies._form', ['company' => new \App\Models\Company()])
                <button class="btn btn-primary">Save Company</button>
            </form>
        </div>
    </div>
@endsection

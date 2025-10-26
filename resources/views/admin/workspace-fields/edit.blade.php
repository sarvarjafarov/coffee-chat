@extends('layouts.admin')

@section('title', 'Edit workspace field')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.workspace-fields.index') }}" class="btn btn-secondary">Back</a>
        <form action="{{ route('admin.workspace-fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Delete this field?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.workspace-fields.update', $field) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.workspace-fields._form')
                <button class="btn btn-primary">Update field</button>
            </form>
        </div>
    </div>
@endsection

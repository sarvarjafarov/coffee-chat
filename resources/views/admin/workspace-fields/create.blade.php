@extends('layouts.admin')

@section('title', 'Create workspace field')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.workspace-fields.index') }}" class="btn btn-secondary">Back</a>
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
            <form action="{{ route('admin.workspace-fields.store') }}" method="POST">
                @csrf
                @include('admin.workspace-fields._form')
                <button class="btn btn-primary">Save field</button>
            </form>
        </div>
    </div>
@endsection

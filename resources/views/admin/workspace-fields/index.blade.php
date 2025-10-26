@extends('layouts.admin')

@section('title', 'Workspace fields')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.workspace-fields.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> New field
        </a>
    </div>
@endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Form</th>
                    <th>Key</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Analytics</th>
                    <th>Active</th>
                    <th>Order</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($fields as $field)
                    <tr>
                        <td>{{ $field->form }}</td>
                        <td><code>{{ $field->key }}</code></td>
                        <td>{{ $field->label }}</td>
                        <td>{{ ucfirst($field->type) }}</td>
                        <td>{!! $field->required ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>' !!}</td>
                        <td>{!! $field->in_analytics ? '<span class="badge badge-info">Yes</span>' : '<span class="badge badge-secondary">No</span>' !!}</td>
                        <td>{!! $field->active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Hidden</span>' !!}</td>
                        <td>{{ $field->position }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.workspace-fields.edit', $field) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.workspace-fields.destroy', $field) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this field?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No workspace fields configured yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Companies')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add Company
        </a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <label for="search" class="sr-only">Search</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Search companies...">
                </div>
                <button type="submit" class="btn btn-outline-primary mb-2 mr-2">Search</button>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary mb-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Industry</th>
                    <th>Location</th>
                    <th>Contacts</th>
                    <th>Coffee Chats</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->industry ?? '—' }}</td>
                        <td>{{ $company->location ?? '—' }}</td>
                        <td>{{ $company->contacts_count }}</td>
                        <td>{{ $company->coffee_chats_count }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this company?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No companies recorded yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $companies->links() }}
        </div>
    </div>
@endsection

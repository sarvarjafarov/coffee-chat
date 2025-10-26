@extends('layouts.admin')

@section('title', 'Contacts')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add Contact
        </a>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="form-row">
                <div class="form-group col-md-4">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" name="search" id="search" value="{{ $filters['search'] }}" placeholder="Name, email, position...">
                </div>
                <div class="form-group col-md-4">
                    <label for="company_id">Company</label>
                    <select name="company_id" id="company_id" class="form-control">
                        <option value="">All companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected($filters['company_id'] === $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary mr-2">Filter</button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->company?->name ?? '—' }}</td>
                        <td>{{ $contact->position ?? '—' }}</td>
                        <td>{{ $contact->email ?? '—' }}</td>
                        <td>{{ $contact->phone ?? '—' }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this contact?');">
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
                        <td colspan="6" class="text-center text-muted py-4">No contacts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection

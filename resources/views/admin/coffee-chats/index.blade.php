@extends('layouts.admin')

@section('title', 'Coffee Chats')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.coffee-chats.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Log Coffee Chat
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Chats</p>
                </div>
                <div class="icon">
                    <i class="fas fa-mug-hot"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['completed'] }}</h3>
                    <p>Completed</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['planned'] }}</h3>
                    <p>Planned / Upcoming</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['follow_up'] }}</h3>
                    <p>Needs Follow-up</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="form-row">
                <div class="form-group col-md-3">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Company, contact, role...">
                </div>
                <div class="form-group col-md-2">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="company_id">Company</label>
                    <select name="company_id" id="company_id" class="form-control">
                        <option value="">All companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected($filters['company_id'] === $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="user_id">Owner</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">All users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($filters['user_id'] === $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary mr-2">Filter</button>
                    <a href="{{ route('admin.coffee-chats.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th>Channels</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($coffeeChats as $chat)
                    <tr>
                        <td>
                            @if($chat->scheduled_at)
                                <span class="d-block font-weight-bold">{{ $chat->scheduled_at->format('M d, Y') }}</span>
                                <span class="text-muted small">{{ $chat->scheduled_at->format('H:i') }} {{ $chat->time_zone ?? '' }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($chat->company)
                                <a href="{{ route('admin.companies.edit', $chat->company) }}">{{ $chat->company->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($chat->contact)
                                <a href="{{ route('admin.contacts.edit', $chat->contact) }}">{{ $chat->contact->name }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $chat->position_title ?? '—' }}</td>
                        <td>
                            <span class="badge badge-pill badge-{{ $chat->status === 'completed' ? 'success' : ($chat->status === 'planned' ? 'info' : ($chat->status === 'follow_up_required' ? 'warning' : 'secondary')) }}">
                                {{ $statusOptions[$chat->status] ?? ucfirst($chat->status) }}
                            </span>
                        </td>
                        <td>{{ $chat->user?->name ?? '—' }}</td>
                        <td>
                            @if($chat->channels->isNotEmpty())
                                @foreach($chat->channels as $channel)
                                    <span class="badge badge-light">{{ $channel->label }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.coffee-chats.show', $chat) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.coffee-chats.edit', $chat) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.coffee-chats.destroy', $chat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coffee chat?');">
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
                        <td colspan="8" class="text-center text-muted py-4">No coffee chats found. Log one to get started!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $coffeeChats->links() }}
        </div>
    </div>
@endsection

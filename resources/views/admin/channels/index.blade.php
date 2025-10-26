@extends('layouts.admin')

@section('title', 'Channels')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.channels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add Channel
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Label</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Coffee Chats</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($channels as $channel)
                    <tr>
                        <td>{{ $channel->label }}</td>
                        <td><code>{{ $channel->slug }}</code></td>
                        <td>{{ $channel->description ?? '—' }}</td>
                        <td>{{ $channel->coffee_chats_count }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.channels.edit', $channel) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.channels.destroy', $channel) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this channel?');">
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
                        <td colspan="5" class="text-center text-muted py-4">No channels configured yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

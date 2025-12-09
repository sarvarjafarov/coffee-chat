@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Feedback inbox</h1>
                    <p class="text-muted mb-0">User-reported bugs, friction, and ideas across the product.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>Category</th>
                                <th>Message</th>
                                <th>Page</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedback as $item)
                                <tr>
                                    <td>{{ optional($item->created_at)->diffForHumans() }}</td>
                                    <td><span class="badge badge-info">{{ $item->category ?? 'unspecified' }}</span></td>
                                    <td style="max-width: 360px;">{{ $item->message }}</td>
                                    <td>
                                        <div class="text-muted small">{{ $item->page_title ?? 'Unknown' }}</div>
                                        <code class="small">{{ $item->page_path ?? '/' }}</code>
                                    </td>
                                    <td class="small">
                                        @if($item->user_id)
                                            User #{{ $item->user_id }}<br>
                                        @endif
                                        @if($item->email)
                                            {{ $item->email }}<br>
                                        @endif
                                        @if($item->name)
                                            {{ $item->name }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->status === 'resolved' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.feedback.update', $item) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $item->status === 'open' ? 'resolved' : 'open' }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                Mark {{ $item->status === 'open' ? 'resolved' : 'open' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted p-3">No feedback yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $feedback->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

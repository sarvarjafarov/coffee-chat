@extends('layouts.admin')

@section('title', $coffeeChat->company?->name ?? 'Coffee Chat')

@section('actions')
    <div class="btn-group float-sm-right">
        <a href="{{ route('admin.coffee-chats.edit', $coffeeChat) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.coffee-chats.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        {{ $coffeeChat->position_title ?? 'Coffee Chat' }}
                    </h5>
                    <div class="d-flex flex-wrap mb-3">
                        <span class="mr-3">
                            <i class="fas fa-user mr-1"></i>
                            {{ $coffeeChat->contact?->name ?? 'N/A' }}
                        </span>
                        <span class="mr-3">
                            <i class="fas fa-building mr-1"></i>
                            {{ $coffeeChat->company?->name ?? 'N/A' }}
                        </span>
                        <span class="mr-3">
                            <i class="fas fa-user-tag mr-1"></i>
                            Owner: {{ $coffeeChat->user?->name ?? 'N/A' }}
                        </span>
                        <span>
                            <i class="fas fa-info-circle mr-1"></i>
                            <span class="badge badge-pill badge-{{ $coffeeChat->status === 'completed' ? 'success' : ($coffeeChat->status === 'planned' ? 'info' : ($coffeeChat->status === 'follow_up_required' ? 'warning' : 'secondary')) }}">
                                {{ $statusOptions[$coffeeChat->status] ?? ucfirst($coffeeChat->status) }}
                            </span>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Scheduled</p>
                            <p>
                                @if($coffeeChat->scheduled_at)
                                    {{ $coffeeChat->scheduled_at->format('M d, Y H:i') }}
                                    {{ $coffeeChat->time_zone ? '('.$coffeeChat->time_zone.')' : '' }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Location</p>
                            <p>
                                {{ $coffeeChat->location ?? '—' }}
                                @if($coffeeChat->is_virtual)
                                    <span class="badge badge-light">Virtual</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Duration</p>
                            <p>{{ $coffeeChat->duration_minutes ? $coffeeChat->duration_minutes.' min' : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Rating</p>
                            <p>{{ $coffeeChat->rating ? $coffeeChat->rating . '/5' : '—' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Reach-out Channels</p>
                        @if($coffeeChat->channels->isNotEmpty())
                            @foreach($coffeeChat->channels as $channel)
                                <span class="badge badge-secondary">{{ $channel->label }}</span>
                            @endforeach
                        @else
                            <p>—</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6>Summary</h6>
                        <p>{{ $coffeeChat->summary ?? '—' }}</p>
                    </div>
                    <div class="mb-4">
                        <h6>Key Takeaways</h6>
                        <p>{{ $coffeeChat->key_takeaways ?? '—' }}</p>
                    </div>
                    <div class="mb-4">
                        <h6>Next Steps</h6>
                        <p>{{ $coffeeChat->next_steps ?? '—' }}</p>
                    </div>
                    <div class="mb-0">
                        <h6>Internal Notes</h6>
                        <p>{{ $coffeeChat->notes ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    Follow-up Tasks
                </div>
                <div class="card-body">
                    @if($coffeeChat->followUpTasks->isEmpty())
                        <p class="text-muted mb-0">No follow-up tasks logged yet.</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach($coffeeChat->followUpTasks as $task)
                                <li class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $task->title }}</strong>
                                        <span class="badge badge-{{ $task->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        Due: {{ $task->due_at ? $task->due_at->format('M d, Y') : '—' }}
                                    </div>
                                    @if($task->notes)
                                        <p class="small mb-0">{{ $task->notes }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="card-footer text-muted small">
                    Task management UI coming soon.
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Metadata
                </div>
                <div class="card-body">
                    <p class="text-muted mb-1">Created</p>
                    <p>{{ $coffeeChat->created_at->format('M d, Y H:i') }}</p>
                    <p class="text-muted mb-1">Last Updated</p>
                    <p>{{ $coffeeChat->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

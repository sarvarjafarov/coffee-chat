@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Mock interview scheduler</span>
            <h1>Mock interviews</h1>
            <p class="text-subtle mb-0">Plan peer/coach sessions, send external links, and capture feedback.</p>
        </div>
        <a href="{{ route('workspace.mock-interviews.create') }}" class="btn btn-primary">
            <span class="mdi mdi-plus-circle-outline me-2"></span>
            Schedule mock
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success workspace-section mb-0">{{ session('status') }}</div>
    @endif

    <div class="workspace-section">
        <div class="workspace-metrics">
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Total</span>
                <span class="workspace-metric-value">{{ $totalInterviews }}</span>
                <p class="text-subtle mb-0 mt-2">All mock sessions tracked.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Upcoming</span>
                <span class="workspace-metric-value">{{ $upcomingInterviews }}</span>
                <p class="text-subtle mb-0 mt-2">Confirmed slots in your timezone.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Completed</span>
                <span class="workspace-metric-value">{{ $completedInterviews }}</span>
                <p class="text-subtle mb-0 mt-2">Wrap-ups with feedback captured.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">No-shows</span>
                <span class="workspace-metric-value">{{ $noShowInterviews }}</span>
                <p class="text-subtle mb-0 mt-2">Use to monitor reliability.</p>
            </div>
        </div>
    </div>

    @if($statusCounts->isNotEmpty())
        <div class="workspace-card workspace-section">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="workspace-eyebrow">Status mix</span>
                <span class="text-subtle small">Bookings by state, including no-shows.</span>
            </div>
            <div class="workspace-status-group">
                @foreach($statusCounts as $status => $count)
                    <div class="workspace-chip">
                        <span class="count">{{ $count }}</span>
                        <span>{{ $statusOptions[$status] ?? ucfirst($status) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="workspace-table workspace-section">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Partner</th>
                    <th scope="col">Status</th>
                    <th scope="col">Reminders</th>
                    <th scope="col">Rating</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interviews as $interview)
                    <tr>
                        <td>
                            @if($interview->scheduled_at)
                                <span class="fw-semibold d-block">{{ $interview->scheduled_at->format('M d, Y') }}</span>
                                <small class="text-subtle d-block">{{ $interview->scheduled_at->format('H:i') }} {{ $interview->time_zone }}</small>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                            @if($interview->join_url)
                                <a href="{{ $interview->join_url }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center gap-1 fw-semibold text-decoration-none">
                                    <span class="mdi mdi-open-in-new"></span>
                                    Join link
                                </a>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold d-block">{{ $typeOptions[$interview->interview_type] ?? ucfirst($interview->interview_type) }}</span>
                            @if($interview->difficulty)
                                <small class="text-subtle d-block">Difficulty: {{ ucfirst($interview->difficulty) }}</small>
                            @endif
                            @if($interview->focus_area)
                                <small class="text-subtle d-block">{{ $interview->focus_area }}</small>
                            @endif
                            @if($interview->duration_minutes)
                                <small class="text-subtle d-block">{{ $interview->duration_minutes }} min</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold d-block">{{ $interview->partner_name ?? '—' }}</span>
                            @if($interview->partner_email)
                                <small class="text-subtle d-block">{{ $interview->partner_email }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge-soft">{{ $statusOptions[$interview->status] ?? $interview->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($interview->reminder_channels ?? [] as $channel)
                                    <span class="badge-soft">{{ ucfirst($channel) }}</span>
                                @endforeach
                                @if(empty($interview->reminder_channels))
                                    <span class="text-subtle">None</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($interview->rating)
                                <span class="workspace-chip">
                                    <span class="count">{{ $interview->rating }}</span>
                                    <span>/5</span>
                                </span>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-2">
                                @if($interview->scheduled_at)
                                    <a href="{{ route('workspace.mock-interviews.ics', $interview) }}" class="btn btn-sm btn-outline-secondary">
                                        <span class="mdi mdi-calendar"></span>
                                        ICS
                                    </a>
                                @endif
                                <a href="{{ route('workspace.mock-interviews.edit', $interview) }}" class="btn btn-sm btn-outline-primary">
                                    <span class="mdi mdi-pencil-outline"></span>
                                    Edit
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('workspace.mock-interviews.destroy', $interview) }}" onsubmit="return confirm('Delete this mock interview?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-subtle py-5">
                            No mock interviews yet. Click <strong>“Schedule mock”</strong> to add your first session.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $interviews->links() }}
    </div>
@endsection

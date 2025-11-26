@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Case practice</span>
            <h1>Case sessions</h1>
            <p class="text-subtle mb-0">Track cases, reflections, and optional LLM feedback opt-ins.</p>
        </div>
        <a href="{{ route('workspace.cases.create') }}" class="btn btn-primary">
            <span class="mdi mdi-plus-circle-outline me-2"></span>
            Log case
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success workspace-section mb-0">{{ session('status') }}</div>
    @endif

    <div class="workspace-section">
        <div class="workspace-metrics">
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Total sessions</span>
                <span class="workspace-metric-value">{{ $totalSessions }}</span>
                <p class="text-subtle mb-0 mt-2">Cases you have logged so far.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Completed</span>
                <span class="workspace-metric-value">{{ $completedSessions }}</span>
                <p class="text-subtle mb-0 mt-2">Closed out with reflection and next steps.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Planned</span>
                <span class="workspace-metric-value">{{ $plannedSessions }}</span>
                <p class="text-subtle mb-0 mt-2">Queued cases on your calendar.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Avg self-score</span>
                <span class="workspace-metric-value">
                    {{ $overallScore !== null ? $overallScore : '—' }}
                </span>
                <p class="text-subtle mb-0 mt-2">Mean across structure, math, insight, and communication.</p>
            </div>
        </div>
    </div>

    @if($statusCounts->isNotEmpty())
        <div class="workspace-card workspace-section">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="workspace-eyebrow">Status mix</span>
                <span class="text-subtle small">Snapshot of where your practice stands.</span>
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

    @if(collect($scoreAverages)->filter()->isNotEmpty())
        <div class="workspace-card workspace-section">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="workspace-eyebrow">Skill snapshot</span>
                <span class="text-subtle small">Average self-scores by dimension.</span>
            </div>
            <div class="workspace-status-group">
                @foreach($scoreFields as $key => $label)
                    <div class="workspace-chip">
                        <span class="count">{{ $scoreAverages[$key] ?? '—' }}</span>
                        <span>{{ $label }}</span>
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
                    <th scope="col">Case</th>
                    <th scope="col">Details</th>
                    <th scope="col">Status</th>
                    <th scope="col">Scores</th>
                    <th scope="col">LLM</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td>
                            @if($session->scheduled_at)
                                <span class="fw-semibold d-block">{{ $session->scheduled_at->format('M d, Y') }}</span>
                                <small class="text-subtle">{{ $session->scheduled_at->format('H:i') }} {{ $session->time_zone }}</small>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold d-block">{{ $session->caseStudy?->title ?? $session->custom_title ?? 'Untitled case' }}</span>
                            @if($session->caseStudy?->difficulty)
                                <small class="text-subtle d-block">Difficulty: {{ ucfirst($session->caseStudy->difficulty) }}</small>
                            @endif
                            @if($session->caseStudy?->industry)
                                <small class="text-subtle d-block">{{ $session->caseStudy->industry }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="badge-soft">
                                    {{ $session->caseStudy?->case_type ? str_replace('_', ' ', ucfirst($session->caseStudy->case_type)) : 'Custom' }}
                                </span>
                                @if($session->duration_minutes)
                                    <small class="text-subtle">{{ $session->duration_minutes }} min</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft">{{ $statusOptions[$session->status] ?? $session->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($scoreFields as $key => $label)
                                    @php($value = data_get($session->self_scores, $key))
                                    <span class="badge-soft">
                                        {{ $label }}: {{ $value ?? '—' }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft">{{ $session->llm_feedback_opt_in ? 'Opted in' : 'Off' }}</span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('workspace.cases.edit', $session) }}" class="btn btn-sm btn-outline-primary">
                                    <span class="mdi mdi-pencil-outline"></span>
                                    Edit
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('workspace.cases.destroy', $session) }}" onsubmit="return confirm('Delete this case session?');">
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
                            No case sessions yet. Click <strong>“Log case”</strong> to start your practice tracker.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        @include('components.workspace-pagination', ['paginator' => $sessions])
    </div>
@endsection

@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Analytics</span>
            <h1>Performance pulse</h1>
            <p class="text-subtle">Track how conversations move through your funnel and which channels perform best.</p>
        </div>
    </div>

    <div class="workspace-section">
        <div class="workspace-metrics">
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Total chats</span>
                <span class="workspace-metric-value">{{ $totalChats }}</span>
                <p class="text-subtle mb-0 mt-2">Every logged conversation across time.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Completed</span>
                <span class="workspace-metric-value">{{ $completedChats }}</span>
                <p class="text-subtle mb-0 mt-2">Finished chats with follow-up delivered.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Active channels</span>
                <span class="workspace-metric-value">{{ count($channelCounts) }}</span>
                <p class="text-subtle mb-0 mt-2">Distinct touchpoints powering relationships.</p>
            </div>
        </div>
    </div>

    <div class="workspace-section">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="workspace-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="workspace-eyebrow">Status mix</span>
                        <span class="text-subtle small">Where each chat sits in the journey.</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @forelse($statusCounts as $status => $count)
                            <li class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(148,163,184,0.16);">
                                <span class="text-subtle text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                <span class="workspace-chip"><span class="count">{{ $count }}</span></span>
                            </li>
                        @empty
                            <li class="text-subtle">No coffee chats logged yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="workspace-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="workspace-eyebrow">Channel mix</span>
                        <span class="text-subtle small">Identify your highest-performing introductions.</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @forelse($channelCounts as $channel => $count)
                            <li class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(148,163,184,0.16);">
                                <span class="text-subtle">{{ $channel }}</span>
                                <span class="workspace-chip"><span class="count">{{ $count }}</span></span>
                            </li>
                        @empty
                            <li class="text-subtle">Log chats to surface channel insights.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($dynamicStats))
        <div class="workspace-card workspace-section">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="workspace-eyebrow">Custom field insights</span>
                <span class="text-subtle small">Breakdowns from the structured data you capture.</span>
            </div>
            <div class="row g-4">
                @foreach($dynamicStats as $stat)
                    <div class="col-md-6">
                        <div class="workspace-card workspace-card--flush h-100" style="padding:1.6rem;">
                            <h5 class="fw-semibold mb-3">{{ $stat['field']->label }}</h5>
                            @if($stat['counts']->isEmpty())
                                <p class="text-subtle mb-0">No data yet.</p>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($stat['counts'] as $value => $count)
                                        <li class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(148,163,184,0.14);">
                                            <span class="text-subtle">{{ $value ?: '—' }}</span>
                                            <span class="workspace-chip"><span class="count">{{ $count }}</span></span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

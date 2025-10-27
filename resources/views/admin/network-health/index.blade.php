@extends('layouts.admin')

@section('title', 'Network Health Assessments')

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Score</th>
                    <th>Monthly contacts</th>
                    <th>Warm intros (Q)</th>
                    <th>Follow-up days</th>
                    <th>Industry diversity</th>
                    <th>Relationship strength</th>
                    <th>Submitted</th>
                </tr>
                </thead>
                <tbody>
                @forelse($assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->email }}</td>
                        <td>
                            <span class="badge badge-{{ $assessment->score >= 80 ? 'success' : ($assessment->score >= 50 ? 'info' : 'warning') }}">
                                {{ $assessment->score }}
                            </span>
                        </td>
                        <td>{{ $assessment->monthly_unique_contacts }}</td>
                        <td>{{ $assessment->warm_intros_last_quarter }}</td>
                        <td>{{ number_format($assessment->average_follow_up_days, 1) }}</td>
                        <td>{{ $assessment->industry_diversity }}</td>
                        <td>{{ $assessment->relationship_strength }}</td>
                        <td>{{ $assessment->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="bg-light text-muted small">
                            <strong>Summary:</strong> {{ $assessment->summary ?? '—' }}<br>
                            @if($assessment->recommendations)
                                <strong>Recommendations:</strong>
                                <ul class="mb-0">
                                    @foreach($assessment->recommendations as $recommendation)
                                        <li>{{ $recommendation }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No assessments recorded yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($assessments->hasPages())
            <div class="card-footer">
                {{ $assessments->links() }}
            </div>
        @endif
    </div>
@endsection

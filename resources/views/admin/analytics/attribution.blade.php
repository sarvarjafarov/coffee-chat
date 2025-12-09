@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Attribution</h1>
                    <p class="text-muted mb-0">Compare first, last, linear, and time-decay credit by source/medium/campaign.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title">Model breakdown</h3>
                            <small class="text-muted">Top 100 rows by credit</small>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Source</th>
                                        <th>Medium</th>
                                        <th>Campaign</th>
                                        <th class="text-right">Credit</th>
                                        <th class="text-right">Conversions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($summary as $row)
                                        <tr>
                                            <td>{{ str_replace('_', ' ', $row->model) }}</td>
                                            <td>{{ $row->source ?? 'direct' }}</td>
                                            <td>{{ $row->medium ?? 'none' }}</td>
                                            <td>{{ $row->campaign ?? '—' }}</td>
                                            <td class="text-right">{{ number_format($row->total_credit, 2) }}</td>
                                            <td class="text-right">{{ $row->conversions }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted p-3">No attribution data yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent conversions</h3>
                        </div>
                        <div class="card-body" style="max-height: 520px; overflow-y: auto;">
                            @forelse($recent as $row)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-primary text-uppercase">{{ str_replace('_', ' ', $row->conversion_type) }}</span>
                                        <small class="text-muted">{{ optional($row->occurred_at)->diffForHumans() }}</small>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        Model: <strong>{{ $row->model }}</strong><br>
                                        Source: <strong>{{ $row->source ?? 'direct' }}</strong> · Medium: <strong>{{ $row->medium ?? 'none' }}</strong><br>
                                        Campaign: <strong>{{ $row->campaign ?? '—' }}</strong><br>
                                        Credit: <strong>{{ number_format($row->credit, 2) }}</strong>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No conversions tracked yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

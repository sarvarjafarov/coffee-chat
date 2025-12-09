@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalUsers }}</h3>
                        <p>Total users</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $new7 }}</h3>
                        <p>New last 7 days</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-week"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $new30 }}</h3>
                        <p>New last 30 days</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $byProvider->sum('total') }}</h3>
                        <p>Tracked signups</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-plus"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Signups by provider</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Provider</th>
                                    <th class="text-right">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byProvider as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->provider === 'password' ? 'Email/Password' : $row->provider) }}</td>
                                        <td class="text-right">{{ $row->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Signups (last 30 days)</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($byDay as $row)
                                    <tr>
                                        <td>{{ $row->day }}</td>
                                        <td class="text-right">{{ $row->total }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted p-3">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Top traffic sources (tracked)</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Medium</th>
                                    <th class="text-right">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSources as $row)
                                    <tr>
                                        <td>{{ $row->source }}</td>
                                        <td>{{ $row->medium }}</td>
                                        <td class="text-right">{{ $row->total }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted p-3">No tracked sources yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-muted small">
                        Uses marketing touchpoints tied to a user (requires analytics beacon events).
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Recent signups</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Signup method</th>
                                    <th class="text-right">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent as $user)
                                    <tr>
                                        <td>
                                            <div>{{ $user->name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </td>
                                        <td>{{ $user->oauth_provider ?? 'email/password' }}</td>
                                        <td class="text-right text-muted small">{{ optional($user->created_at)->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted p-3">No users yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

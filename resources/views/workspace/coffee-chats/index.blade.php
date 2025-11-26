@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Coffee chat log</span>
            <h1>My coffee chats</h1>
            <p class="text-subtle">Log conversations, track follow-ups, and monitor momentum.</p>
        </div>
        <a href="{{ route('workspace.coffee-chats.create') }}" class="btn btn-primary">
            <span class="mdi mdi-plus-circle-outline me-2"></span>
            Log coffee chat
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success workspace-section mb-0">{{ session('status') }}</div>
    @endif

    <style>
        .coffee-table {
            border-radius: 26px;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff 0%, rgba(247,250,255,0.96) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 34px 74px -54px rgba(15,23,42,0.25);
        }
        .coffee-table thead th {
            background: linear-gradient(90deg, rgba(236,245,255,0.9), rgba(246,252,255,0.9));
            letter-spacing: 0.16em;
        }
        .coffee-table tbody tr {
            transition: transform 0.14s ease, box-shadow 0.14s ease, background 0.14s ease;
        }
        .coffee-table tbody tr:hover {
            background: rgba(236,247,255,0.75);
            transform: translateY(-1px);
            box-shadow: inset 0 0 0 999px rgba(14,165,233,0.03);
        }
        .coffee-contact {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            min-width: 220px;
        }
        .coffee-contact .name {
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
        }
        .coffee-contact .meta {
            color: rgba(71,85,105,0.78);
            font-size: 0.9rem;
        }
        .coffee-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            background: rgba(15,23,42,0.04);
            color: rgba(30,41,59,0.82);
            font-weight: 600;
            font-size: 0.9rem;
        }
        .coffee-chip.status-planned { background: rgba(14,165,233,0.12); color: rgba(14,165,233,0.9); }
        .coffee-chip.status-completed { background: rgba(34,197,94,0.12); color: rgba(34,197,94,0.9); }
        .coffee-chip.status-cancelled { background: rgba(248,113,113,0.12); color: rgba(248,113,113,0.9); }
        .coffee-chip.status-follow_up_required { background: rgba(234,179,8,0.16); color: rgba(202,138,4,0.9); }
        .coffee-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: rgba(71,85,105,0.78);
            font-weight: 600;
        }
        .coffee-chat-manage-link {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.35rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent-strong);
            text-decoration: none;
        }

        .coffee-chat-manage-link .mdi {
            font-size: 1rem;
        }

        .coffee-chat-manage-link:hover {
            color: var(--accent);
        }
    </style>

    <div class="workspace-section">
        <div class="workspace-metrics">
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Total chats</span>
                <span class="workspace-metric-value">{{ $totalChats }}</span>
                <p class="text-subtle mb-0 mt-2">Conversations logged across every channel.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Completed</span>
                <span class="workspace-metric-value">{{ $completedChats }}</span>
                <p class="text-subtle mb-0 mt-2">Chats with follow-ups wrapped and archived.</p>
            </div>
            <div class="workspace-metric-card">
                <span class="workspace-eyebrow">Active channels</span>
                <span class="workspace-metric-value">{{ $activeChannels }}</span>
                <p class="text-subtle mb-0 mt-2">Distinct outreach channels driving momentum.</p>
            </div>
        </div>
    </div>

    @if($statusCounts->isNotEmpty())
        <div class="workspace-card workspace-section">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="workspace-eyebrow">Status mix</span>
                <span class="text-subtle small">Snapshot of your current pipeline health.</span>
            </div>
            <div class="workspace-status-group">
                @foreach($statusCounts as $status => $count)
                    <div class="workspace-chip">
                        <span class="count">{{ $count }}</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="workspace-table workspace-section coffee-table">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Company</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Status</th>
                    <th scope="col">Channels</th>
                    <th scope="col">Rating</th>
                    @foreach($dynamicFields as $field)
                        <th scope="col">{{ $field->label }}</th>
                    @endforeach
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chats as $chat)
                    <tr>
                        <td>
                            @if($chat->scheduled_at)
                                <div class="d-flex flex-column gap-1">
                                    <span class="fw-semibold">{{ $chat->scheduled_at->format('M d, Y') }}</span>
                                    <small class="text-subtle">{{ $chat->scheduled_at->format('H:i') }} {{ $chat->time_zone }}</small>
                                </div>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="coffee-meta">
                                <span class="mdi mdi-domain"></span>
                                {{ $chat->company?->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($chat->contact)
                                <div class="coffee-contact">
                                    <span class="name">{{ $chat->contact->name }}</span>
                                    @if($chat->position_title)
                                        <span class="meta">{{ $chat->position_title }}</span>
                                    @endif
                                    <a href="{{ route('workspace.coffee-chats.edit', $chat) }}" class="coffee-chat-manage-link">
                                        <span class="mdi mdi-pencil-outline"></span>
                                        Manage
                                    </a>
                                </div>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="coffee-chip status-{{ $chat->status }}">
                                {{ $statusOptions[$chat->status] ?? $chat->status }}
                            </span>
                        </td>
                        <td>
                            @forelse($chat->channels as $channel)
                                <span class="coffee-chip">{{ $channel->label }}</span>
                            @empty
                                <span class="text-subtle">—</span>
                            @endforelse
                        </td>
                        <td>
                            @if($chat->rating)
                                <span class="coffee-chip">
                                    <span class="mdi mdi-star-outline"></span>
                                    {{ $chat->rating }}/5
                                </span>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        @foreach($dynamicFields as $field)
                            @php($value = data_get($chat->extras, $field->key))
                            <td>
                                @if(is_array($value))
                                    {{ implode(', ', array_filter($value)) ?: '—' }}
                                @elseif(is_bool($value))
                                    {{ $value ? 'Yes' : 'No' }}
                                @else
                                    {{ $value ?? '—' }}
                                @endif
                            </td>
                        @endforeach
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('workspace.coffee-chats.edit', $chat) }}" class="btn btn-sm btn-outline-primary">
                                    <span class="mdi mdi-pencil-outline"></span>
                                    Edit
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('workspace.coffee-chats.destroy', $chat) }}" onsubmit="return confirm('Delete this coffee chat?');">
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
                        <td colspan="{{ 7 + $dynamicFields->count() }}" class="text-center text-subtle py-5">
                            You have no coffee chats yet. Click <strong>“Log coffee chat”</strong> to add your first.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        @include('components.workspace-pagination', ['paginator' => $chats])
    </div>
@endsection

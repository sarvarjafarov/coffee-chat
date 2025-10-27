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

    <div class="workspace-table workspace-section">
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
                                <span class="fw-semibold d-block">{{ $chat->scheduled_at->format('M d, Y') }}</span>
                                <small class="text-subtle">{{ $chat->scheduled_at->format('H:i') }} {{ $chat->time_zone }}</small>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td>{{ $chat->company?->name ?? '—' }}</td>
                        <td>
                            @if($chat->contact)
                                <span class="fw-semibold d-block">{{ $chat->contact->name }}</span>
                                <a href="{{ route('workspace.coffee-chats.edit', $chat) }}" class="coffee-chat-manage-link">
                                    <span class="mdi mdi-pencil-outline"></span>
                                    Manage
                                </a>
                            @else
                                <span class="text-subtle">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-soft">{{ $statusOptions[$chat->status] ?? $chat->status }}</span>
                        </td>
                        <td>
                            @forelse($chat->channels as $channel)
                                <span class="badge-channel">{{ $channel->label }}</span>
                            @empty
                                <span class="text-subtle">—</span>
                            @endforelse
                        </td>
                        <td>
                            @if($chat->rating)
                                <span class="workspace-chip">
                                    <span class="count">{{ $chat->rating }}</span>
                                    <span>/5</span>
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

    <div class="mt-4 d-flex justify-content-center">
        {{ $chats->links() }}
    </div>
@endsection

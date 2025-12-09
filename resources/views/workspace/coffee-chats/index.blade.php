@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Coffee chat log</span>
            <h1>My coffee chats</h1>
            <p class="text-subtle">Log conversations, track follow-ups, and monitor momentum.</p>
        </div>
        <a
            href="{{ route('workspace.coffee-chats.create') }}"
            class="btn btn-primary"
            data-analytics-event="coffee_chat_new_click"
            data-location="list_header"
        >
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
        .coffee-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .coffee-card {
            background: linear-gradient(180deg, #ffffff 0%, rgba(247,250,255,0.96) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 20px;
            padding: 1rem;
            box-shadow: 0 28px 60px -48px rgba(15,23,42,0.35);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .coffee-card__header, .coffee-card__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .coffee-card__date {
            font-weight: 700;
            color: #0f172a;
        }
        .coffee-card__time {
            font-size: 0.85rem;
        }
        .coffee-card__body {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .coffee-card__row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .coffee-card__contact {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .coffee-card__name {
            font-weight: 700;
            color: #0f172a;
        }
        .coffee-card__manage {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 600;
            color: var(--accent-strong);
            text-decoration: none;
        }
        .coffee-card__manage:hover { color: var(--accent); }
        .coffee-card__channels {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }
        .coffee-card__fields {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.5rem 0.75rem;
        }
        .coffee-card__field .label { font-size: 0.85rem; }
        .coffee-card__field .value { font-weight: 600; color: #0f172a; }
        .coffee-card__actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        @media (max-width: 640px) {
            .coffee-card-grid { grid-template-columns: 1fr; }
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

    @if(!empty($nudges))
        <div class="workspace-section">
            <div class="workspace-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="mdi mdi-lightbulb-outline text-warning"></span>
                    <span class="workspace-eyebrow">Keep momentum</span>
                </div>
                <div class="d-grid gap-2" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
                    @foreach($nudges as $nudge)
                        <div class="p-3 rounded" style="background: rgba(14,165,233,0.08); border: 1px solid rgba(14,165,233,0.18);">
                            <div class="fw-semibold">{{ $nudge['title'] }}</div>
                            <div class="text-subtle">{{ $nudge['body'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(!empty($progressMetrics))
        <div class="workspace-section">
            <div class="d-grid gap-3" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <div class="workspace-card h-100">
                    <span class="workspace-eyebrow">Streak</span>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="mb-0">
                            <span class="mdi mdi-fire text-warning"></span>
                            {{ $progressMetrics['current_streak'] ?? 0 }} days
                        </h2>
                        <small class="text-subtle">Longest: {{ $progressMetrics['longest_streak'] ?? 0 }}</small>
                    </div>
                    <p class="text-subtle mb-0 mt-1">Keep the flame alive with daily momentum.</p>
                </div>

                <div class="workspace-card h-100">
                    <span class="workspace-eyebrow">Weekly target</span>
                    @php
                        $weeklyProgress = $progressMetrics['weekly_progress'] ?? 0;
                        $weeklyPercent = (int) round($weeklyProgress * 100);
                    @endphp
                    <div class="fw-semibold mb-1">
                        {{ $progressMetrics['weekly_completed'] ?? 0 }} / {{ $progressMetrics['weekly_goal'] ?? 0 }} chats this week
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $weeklyPercent }}%;" aria-valuenow="{{ $weeklyPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @if(($progressMetrics['weekly_remaining'] ?? 0) > 0)
                        <p class="text-subtle mb-0 mt-2">
                            {{ $progressMetrics['weekly_remaining'] }} to go to hit your goal.
                        </p>
                    @else
                        <p class="text-subtle mb-0 mt-2">Goal met—nice work.</p>
                    @endif
                </div>

                <div class="workspace-card h-100">
                    <span class="workspace-eyebrow">XP</span>
                    @php
                        $levelProgress = $progressMetrics['level_progress'] ?? 0;
                        $levelPercent = (int) round($levelProgress * 100);
                    @endphp
                    <div class="fw-semibold mb-1">
                        Level {{ $progressMetrics['level'] ?? 1 }} · {{ $progressMetrics['xp_total'] ?? 0 }} XP
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $levelPercent }}%;" aria-valuenow="{{ $levelPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-subtle mb-0 mt-2">XP builds as you complete chats with notes and structure.</p>
                </div>
            </div>
        </div>
    @endif

    @if(!empty($achievements))
        <div class="workspace-section">
            <div class="workspace-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="workspace-eyebrow">Achievements</span>
                    <small class="text-subtle">Unlocked badges</small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($achievements as $achievement)
                        <span class="workspace-chip">
                            <span class="mdi mdi-trophy-outline text-warning"></span>
                            <span class="fw-semibold">{{ $achievement->title }}</span>
                            <small class="text-muted d-block" style="font-size: 0.8rem;">{{ $achievement->description }}</small>
                        </span>
                    @empty
                        <span class="text-subtle">No achievements yet—complete chats to unlock.</span>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    @if(!empty($progressMetrics) || !empty($newAchievements))
        <div id="milestone-toast" style="position: fixed; top: 16px; right: 16px; z-index: 1040; display: none; max-width: 320px;">
            <div class="alert alert-success d-flex align-items-start gap-2 mb-0" style="box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
                <span class="mdi mdi-party-popper fs-4"></span>
                <div>
                    <div class="fw-bold" id="milestone-title">Milestone unlocked</div>
                    <div id="milestone-body" class="small mb-1"></div>
                    <button class="btn btn-sm btn-outline-success" id="milestone-close">Nice!</button>
                </div>
            </div>
        </div>
    @endif

    @if($nextReminderChat)
        @php
            $reminderAt = $nextReminderChat->scheduled_at?->copy();
            if ($reminderAt) {
                $reminderAt->subMinutes(30);
            }
            $title = trim(collect([$nextReminderChat->contact?->name, $nextReminderChat->company?->name])->filter()->implode(' — ')) ?: 'Coffee chat';
        @endphp
        <div class="workspace-card workspace-section" id="browser-reminder-card"
             data-scheduled-at="{{ $reminderAt?->toIso8601String() }}"
             data-chat-title="{{ $title }}"
             data-location="{{ $nextReminderChat->location ?? '' }}">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start">
                <div>
                    <span class="workspace-eyebrow">Browser reminder</span>
                    <h3 class="mb-2">Enable a push alert for your next chat</h3>
                    <p class="text-subtle mb-1">
                        We’ll nudge you 30 minutes before {{ $title }} on
                        <strong>{{ $nextReminderChat->scheduled_at?->format('M d, Y · g:i A') }}</strong>
                        {{ $nextReminderChat->time_zone ?: config('app.timezone') }}.
                    </p>
                    <small class="text-muted">Requires browser notification permission.</small>
                    <div class="text-muted small mt-2" id="reminder-feedback" aria-live="polite"></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary" id="enable-reminder-btn">
                        <span class="mdi mdi-bell-ring-outline me-1"></span>
                        Enable reminder
                    </button>
                </div>
            </div>
        </div>
    @endif

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

    <div class="workspace-section">
        <div class="coffee-card-grid">
            @forelse($chats as $chat)
                <div class="coffee-card">
                    <div class="coffee-card__header">
                        <div>
                            @if($chat->scheduled_at)
                                <div class="coffee-card__date">{{ $chat->scheduled_at->format('M d, Y') }}</div>
                                <div class="coffee-card__time text-subtle">{{ $chat->scheduled_at->format('H:i') }} {{ $chat->time_zone }}</div>
                            @else
                                <div class="coffee-card__date text-subtle">No date</div>
                            @endif
                        </div>
                        <span class="coffee-chip status-{{ $chat->status }}">
                            {{ $statusOptions[$chat->status] ?? ucfirst($chat->status) }}
                        </span>
                    </div>

                    <div class="coffee-card__body">
                        <div class="coffee-card__row">
                            <span class="coffee-meta">
                                <span class="mdi mdi-domain"></span>
                                {{ $chat->company?->name ?? '—' }}
                            </span>
                            @if($chat->rating)
                                <span class="coffee-chip">
                                    <span class="mdi mdi-star-outline"></span>
                                    {{ $chat->rating }}/5
                                </span>
                            @endif
                        </div>

                        <div class="coffee-card__contact">
                            <div>
                                <div class="coffee-card__name">{{ $chat->contact->name ?? 'Unnamed contact' }}</div>
                                @if($chat->position_title)
                                    <div class="text-subtle">{{ $chat->position_title }}</div>
                                @endif
                            </div>
                            <a href="{{ route('workspace.coffee-chats.edit', $chat) }}" class="coffee-card__manage">
                                <span class="mdi mdi-pencil-outline"></span> Manage
                            </a>
                        </div>

                        <div class="coffee-card__channels">
                            @forelse($chat->channels as $channel)
                                <span class="coffee-chip">{{ $channel->label }}</span>
                            @empty
                                <span class="text-subtle">No channels</span>
                            @endforelse
                        </div>

                        @if($dynamicFields->isNotEmpty())
                            <div class="coffee-card__fields">
                                @foreach($dynamicFields as $field)
                                    @php($value = data_get($chat->extras, $field->key))
                                    <div class="coffee-card__field">
                                        <div class="label text-subtle">{{ $field->label }}</div>
                                        <div class="value">
                                            @if(is_array($value))
                                                {{ implode(', ', array_filter($value)) ?: '—' }}
                                            @elseif(is_bool($value))
                                                {{ $value ? 'Yes' : 'No' }}
                                            @else
                                                {{ $value ?? '—' }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="coffee-card__footer">
                        <div class="coffee-card__actions">
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
                    </div>
                </div>
            @empty
                <div class="text-center text-subtle py-5">
                    You have no coffee chats yet. Click <strong>“Log coffee chat”</strong> to add your first.
                </div>
            @endforelse
        </div>
    </div>

    <div>
        @include('components.workspace-pagination', ['paginator' => $chats])
    </div>

    @if($nextReminderChat)
        <script>
            (() => {
                const card = document.getElementById('browser-reminder-card');
                if (!card || !('Notification' in window)) {
                    const feedback = document.getElementById('reminder-feedback');
                    if (feedback && !('Notification' in window)) {
                        feedback.textContent = 'Push notifications are not supported in this browser.';
                    }
                    return;
                }

                const enableBtn = document.getElementById('enable-reminder-btn');
                const feedback = document.getElementById('reminder-feedback');
                const scheduledAt = card.dataset.scheduledAt;
                const chatTitle = card.dataset.chatTitle || 'Coffee chat';
                const location = card.dataset.location || 'TBD';

                const showFeedback = (message, isError = false) => {
                    if (!feedback) return;
                    feedback.textContent = message;
                    feedback.style.color = isError ? '#b91c1c' : '#475569';
                };

                const scheduleNotification = () => {
                    if (!scheduledAt) {
                        showFeedback('No upcoming chat within the next 3 days to schedule.', true);
                        return;
                    }

                    const reminderTime = new Date(scheduledAt).getTime();
                    const delay = reminderTime - Date.now();

                    const safeDelay = delay > 0 ? delay : 5000;

                    setTimeout(() => {
                        new Notification('Coffee chat starting soon', {
                            body: `${chatTitle} in ~30 minutes • ${location}`,
                            icon: '/favicon.ico'
                        });
                    }, safeDelay);

                    enableBtn.disabled = true;
                    showFeedback('Reminder scheduled. Leave this tab open to receive it.', false);
                };

                const handlePermission = (permission) => {
                    if (permission === 'granted') {
                        showFeedback('Permission granted. Scheduling your reminder...', false);
                        scheduleNotification();
                    } else if (permission === 'denied') {
                        showFeedback('Notifications are blocked. Please enable them in your browser settings.', true);
                    } else {
                        showFeedback('Notification permission dismissed. Try again when ready.', true);
                    }
                };

                const requestPermissionAndSchedule = () => {
                    if (Notification.permission === 'granted') {
                        scheduleNotification();
                        return;
                    }

                    Notification.requestPermission().then(handlePermission).catch(() => {
                        showFeedback('Unable to request notification permission.', true);
                    });
                };

                enableBtn?.addEventListener('click', requestPermissionAndSchedule);

                if (Notification.permission === 'granted') {
                    showFeedback('Notifications are already allowed. Click to schedule the reminder.', false);
                }
            })();
        </script>
    @endif

    @if(!empty($progressMetrics))
        <script>
            (() => {
                const toast = document.getElementById('milestone-toast');
                if (!toast) return;

                const titleEl = document.getElementById('milestone-title');
                const bodyEl = document.getElementById('milestone-body');
                const closeBtn = document.getElementById('milestone-close');

                const metrics = @json($progressMetrics);
                const achieved = [];

                const newAchievements = @json($newAchievements ?? []);
                if (Array.isArray(newAchievements)) {
                    newAchievements.forEach(item => {
                        achieved.push({
                            key: `achv_${item.slug}`,
                            title: item.title || 'Achievement unlocked',
                            body: item.description || ''
                        });
                    });
                }

                if ((metrics.weekly_remaining ?? 1) === 0 && (metrics.weekly_goal ?? 0) > 0) {
                    achieved.push({ key: 'weekly_goal', title: 'Weekly goal smashed', body: 'You hit your chat target for the week. Keep the momentum!' });
                }

                if ((metrics.current_streak ?? 0) >= 7) {
                    achieved.push({ key: 'streak_7', title: '7-day streak', body: 'A full week of coffee chats. That is consistency.' });
                }

                if ((metrics.longest_streak ?? 0) >= 30) {
                    achieved.push({ key: 'streak_30', title: '30-day streak', body: 'Thirty days of steady outreach. Legendary.' });
                }

                if ((metrics.total_completed ?? 0) >= 25) {
                    achieved.push({ key: 'chats_25', title: '25 chats completed', body: 'You have logged 25 coffee chats. Network compounding in action.' });
                } else if ((metrics.total_completed ?? 0) >= 10) {
                    achieved.push({ key: 'chats_10', title: '10 chats completed', body: 'Double digits reached. Keep going.' });
                }

                const level = metrics.level ?? 1;
                const levelProgress = metrics.level_progress ?? 0;
                if (levelProgress === 0 && (metrics.xp_total ?? 0) > 0) {
                    achieved.push({ key: `level_${level}`, title: `Level ${level} unlocked`, body: 'New level reached. Onward.' });
                }

                if (!achieved.length) return;

                const storageKey = 'coffeechat_milestones_shown';
                let seen = [];
                try {
                    seen = JSON.parse(localStorage.getItem(storageKey) || '[]');
                } catch (e) {
                    seen = [];
                }

                const next = achieved.find(item => !seen.includes(item.key));
                if (!next) return;

                const show = () => {
                    titleEl.textContent = next.title;
                    bodyEl.textContent = next.body;
                    toast.style.display = 'block';
                    toast.style.opacity = 1;
                };

                const hide = () => {
                    toast.style.display = 'none';
                    if (!seen.includes(next.key)) {
                        seen.push(next.key);
                        localStorage.setItem(storageKey, JSON.stringify(seen));
                    }
                };

                closeBtn?.addEventListener('click', hide);
                setTimeout(show, 500);
            })();
        </script>
    @endif
@endsection

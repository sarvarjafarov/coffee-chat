@extends('layouts.site')

@section('content')
    <style>
        .workspace-shell {
            position: relative;
            width: clamp(320px, 94vw, 1200px);
            margin: clamp(2.6rem, 6vw, 3.6rem) auto;
            padding: clamp(3rem, 6vw, 4rem);
            border-radius: 44px;
            background:
                radial-gradient(circle at 6% -10%, rgba(56,189,248,0.28), transparent 58%),
                radial-gradient(circle at 92% 2%, rgba(14,165,233,0.2), transparent 62%),
                linear-gradient(180deg, rgba(244,251,255,0.94) 0%, rgba(255,255,255,0.9) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 60px 120px -68px rgba(15,23,42,0.28);
            backdrop-filter: blur(14px);
            overflow: hidden;
            color: var(--text-primary);
        }
        .workspace-shell::before {
            content: "";
            position: absolute;
            inset: -35% -35% auto;
            height: 160%;
            background: repeating-linear-gradient(115deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 20px);
            opacity: 0.6;
            pointer-events: none;
        }
        .workspace-shell::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(120% 140% at 50% -25%, rgba(255,255,255,0.8), transparent 60%);
            pointer-events: none;
        }
        .workspace-shell > * {
            position: relative;
            z-index: 1;
        }
        .workspace-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            margin-bottom: clamp(1.8rem, 4vw, 2.4rem);
        }
        .workspace-header h1 {
            margin: 0;
            font-size: clamp(1.75rem, 2.8vw, 2.4rem);
            font-weight: 700;
            color: var(--text-primary);
        }
        .workspace-header p {
            margin: 0;
            color: rgba(71,85,105,0.78);
        }
        .workspace-header .btn {
            border-radius: 999px;
            padding: 0.78rem 1.85rem;
            font-weight: 600;
            box-shadow: 0 20px 40px -24px rgba(14,165,233,0.35);
        }
        .workspace-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding: 0.85rem;
            margin-bottom: clamp(2.4rem, 5vw, 3.2rem);
            border-radius: 999px;
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 32px 70px -48px rgba(15,23,42,0.22);
        }
        .workspace-nav a {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1.4rem;
            border-radius: 999px;
            border: 1px solid transparent;
            background: transparent;
            color: rgba(51,65,85,0.78);
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
        .workspace-nav a i {
            font-size: 1.1rem;
            color: rgba(14,165,233,0.78);
        }
        .workspace-nav a:hover,
        .workspace-nav a.active {
            color: var(--accent-strong);
            border-color: rgba(14,165,233,0.32);
            background: rgba(14,165,233,0.12);
            box-shadow: 0 26px 48px -32px rgba(14,165,233,0.35);
            transform: translateY(-2px);
        }
        .workspace-section {
            margin-bottom: clamp(2.1rem, 4vw, 3rem);
        }
        .workspace-card {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, rgba(244,251,255,0.94) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 28px;
            padding: clamp(1.8rem, 4vw, 2.45rem);
            box-shadow: 0 36px 80px -52px rgba(15,23,42,0.2);
        }
        .workspace-card--flush {
            padding: 0;
        }
        .workspace-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: clamp(1rem, 2vw, 1.4rem);
        }
        .workspace-metric-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.92) 100%);
            border: 1px solid rgba(148,163,184,0.16);
            border-radius: 24px;
            padding: 1.6rem;
            box-shadow: 0 26px 54px -42px rgba(15,23,42,0.2);
        }
        .workspace-metric-card::after {
            content: "";
            position: absolute;
            inset: auto -25% -40% -25%;
            height: 60%;
            background: radial-gradient(60% 90% at 50% 100%, rgba(14,165,233,0.12), transparent 70%);
        }
        .workspace-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }
        .workspace-metric-value {
            font-size: clamp(2.1rem, 3vw, 2.6rem);
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 1.1rem;
        }
        .workspace-table {
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(148,163,184,0.16);
            box-shadow: 0 36px 76px -50px rgba(15,23,42,0.18);
            background: rgba(255,255,255,0.95);
        }
        .workspace-table table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            color: rgba(15,23,42,0.92);
        }
        .workspace-table thead th {
            padding: 0.95rem 1.2rem;
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(71,85,105,0.75);
            background: rgba(244,251,255,0.92);
            border-bottom: 1px solid rgba(148,163,184,0.18);
        }
        .workspace-table tbody tr {
            background: rgba(255,255,255,0.96);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .workspace-table tbody tr:nth-child(even) {
            background: rgba(244,251,255,0.7);
        }
        .workspace-table tbody td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid rgba(148,163,184,0.14);
            vertical-align: middle;
        }
        .workspace-table tbody tr:last-child td {
            border-bottom: none;
        }
        .workspace-table tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: inset 0 0 0 999px rgba(14,165,233,0.04);
        }
        .workspace-table .text-subtle {
            color: rgba(100,116,139,0.78) !important;
        }
        .workspace-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.38rem 0.9rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            border: 1px solid rgba(14,165,233,0.24);
            color: rgba(15,23,42,0.82);
            font-weight: 600;
            font-size: 0.82rem;
        }
        .workspace-chip .count {
            font-weight: 700;
            color: var(--accent-strong);
        }
        .workspace-status-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }
        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
        }
        .badge-channel {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.85rem;
            border-radius: 999px;
            background: rgba(244,251,255,0.9);
            border: 1px solid rgba(148,163,184,0.18);
            color: rgba(51,65,85,0.82);
            font-size: 0.78rem;
            font-weight: 600;
        }
        .text-subtle {
            color: rgba(100,116,139,0.8) !important;
        }
        .workspace-form label {
            color: rgba(71,85,105,0.85);
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }
        .workspace-form .form-control,
        .workspace-form .form-select,
        .workspace-form textarea {
            background: rgba(255,255,255,0.95);
            border-color: rgba(148,163,184,0.35);
            color: var(--text-primary);
            border-radius: 14px;
            padding: 0.72rem 1rem;
            box-shadow: inset 0 1px 3px rgba(148,163,184,0.12);
        }
        .workspace-form .form-control::placeholder,
        .workspace-form .form-select::placeholder,
        .workspace-form textarea::placeholder {
            color: rgba(148,163,184,0.75);
        }
        .workspace-form .form-control:focus,
        .workspace-form .form-select:focus,
        .workspace-form textarea:focus {
            border-color: rgba(14,165,233,0.45);
            box-shadow: 0 0 0 0.2rem rgba(14,165,233,0.18);
            background: #fff;
        }
        .workspace-form .form-check-label {
            color: rgba(71,85,105,0.85);
        }
        .workspace-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(148,163,184,0.25), transparent);
            margin: clamp(1.8rem, 3vw, 2.4rem) 0;
        }
    </style>

    @php($customMenuItems = \App\Models\WorkspaceMenuItem::whereNull('user_id')->orderBy('label')->get())

    <div class="workspace-shell">
        <nav class="workspace-nav">
            <a href="{{ route('workspace.coffee-chats.index') }}" class="{{ request()->routeIs('workspace.coffee-chats.*') ? 'active' : '' }}">
                <i class="mdi mdi-coffee-outline"></i>
                <span>Coffee chats</span>
            </a>
            <a href="{{ route('workspace.coffee-chats.calendar') }}" class="{{ request()->routeIs('workspace.coffee-chats.calendar') ? 'active' : '' }}">
                <i class="mdi mdi-calendar-month-outline"></i>
                <span>Calendar</span>
            </a>
            <a href="{{ route('workspace.analytics.index') }}" class="{{ request()->routeIs('workspace.analytics.*') ? 'active' : '' }}">
                <i class="mdi mdi-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="{{ route('pricing') }}" class="{{ request()->routeIs('pricing') ? 'active' : '' }}">
                <i class="mdi mdi-currency-usd"></i>
                <span>Pricing</span>
            </a>
            <a href="{{ route('workspace.team-finder.index') }}" class="{{ request()->routeIs('workspace.team-finder.*') ? 'active' : '' }}">
                <i class="mdi mdi-account-multiple-outline"></i>
                <span>Team finder</span>
            </a>
            <a href="{{ route('workspace.profile') }}" class="{{ request()->routeIs('workspace.profile') ? 'active' : '' }}">
                <i class="mdi mdi-account-circle-outline"></i>
                <span>Profile</span>
            </a>
            @foreach($customMenuItems as $item)
                <a href="{{ $item->url }}" target="_blank" rel="noopener">
                    <i class="mdi mdi-open-in-new"></i>
                    <span>{{ $item->label }}</span>
                </a>
            @endforeach
        </nav>

        <div>
            @yield('workspace-content')
        </div>
    </div>
@endsection

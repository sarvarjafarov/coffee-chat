<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CoffeeChat OS') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @php($viteManifest = public_path('build/manifest.json'))
        @if (file_exists($viteManifest))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css">
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
        @endif

        <style>
            body {
                font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: linear-gradient(140deg, rgba(244,251,255,0.85) 0%, #ffffff 45%, rgba(226,241,255,0.85) 100%);
                min-height: 100vh;
                color: #0f172a;
            }

            .auth-shell {
                width: min(1100px, 94vw);
                margin: clamp(2.5rem, 6vw, 4.5rem) auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: clamp(2rem, 5vw, 3rem);
                background: rgba(255,255,255,0.92);
                border-radius: 40px;
                border: 1px solid rgba(148,163,184,0.18);
                box-shadow: 0 48px 110px -62px rgba(15, 23, 42, 0.26);
                overflow: hidden;
                position: relative;
            }

            .auth-shell::before {
                content: "";
                position: absolute;
                inset: 0;
                background: repeating-linear-gradient(120deg, rgba(148,163,184,0.08) 0, rgba(148,163,184,0.08) 4px, transparent 4px, transparent 24px);
                opacity: 0.5;
                pointer-events: none;
            }

            .auth-shell > * {
                position: relative;
                z-index: 1;
            }

            .auth-info {
                padding: clamp(2.2rem, 5vw, 3.4rem);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 2rem;
                background: linear-gradient(180deg, rgba(14,165,233,0.14) 0%, rgba(14,165,233,0) 90%);
            }

            .auth-info h1 {
                font-size: clamp(2rem, 2.8vw, 2.6rem);
                line-height: 1.1;
                font-weight: 700;
            }

            .auth-info p {
                color: rgba(71,85,105,0.82);
                font-size: 1rem;
                line-height: 1.65;
                max-width: 30rem;
            }

            .auth-testimonials {
                display: grid;
                gap: 1.4rem;
            }

            .auth-quote {
                padding: 1.2rem 1.4rem;
                border-radius: 18px;
                background: rgba(255,255,255,0.85);
                border: 1px solid rgba(148,163,184,0.2);
                box-shadow: 0 20px 46px -32px rgba(15,23,42,0.18);
            }

            .auth-quote strong {
                display: block;
                margin-top: 0.75rem;
                color: rgba(37,99,235,0.95);
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                font-size: 0.72rem;
            }

            .auth-form-pane {
                padding: clamp(2.4rem, 6vw, 3.6rem);
                background: rgba(255,255,255,0.95);
                border-left: 1px solid rgba(148,163,184,0.16);
            }

            .auth-form-pane h2 {
                font-size: 1.75rem;
                font-weight: 600;
                margin-bottom: 0.6rem;
            }

            .auth-form-pane .lead {
                color: rgba(71,85,105,0.78);
                margin-bottom: 1.8rem;
            }

            .auth-form-pane form, .auth-form-pane-inner form {
                display: grid;
                gap: 1.2rem;
            }

            .auth-form-pane label, .auth-form-pane-inner label {
                font-weight: 600;
                font-size: 0.88rem;
                color: rgba(71,85,105,0.85);
            }

            .auth-form-pane input[type="email"],
            .auth-form-pane input[type="password"],
            .auth-form-pane input[type="text"],
            .auth-form-pane-inner input[type="email"],
            .auth-form-pane-inner input[type="password"],
            .auth-form-pane-inner input[type="text"] {
                border-radius: 16px;
                border: 1px solid rgba(148,163,184,0.25);
                background: rgba(255,255,255,0.96);
                padding: 0.85rem 1.1rem;
                font-size: 1rem;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-form-pane input:focus, .auth-form-pane-inner input:focus {
                outline: none;
                border-color: rgba(14,165,233,0.45);
                box-shadow: 0 0 0 3px rgba(14,165,233,0.18);
            }

            .auth-form-pane button[type="submit"],
            .auth-form-pane a.btn-primary,
            .auth-form-pane-inner button[type="submit"],
            .auth-form-pane-inner a.btn-primary {
                border-radius: 999px;
                background: linear-gradient(125deg, rgba(14,165,233,0.95), rgba(37,99,235,0.85));
                color: #fff;
                font-weight: 600;
                padding: 0.9rem 1.6rem;
                border: none;
                box-shadow: 0 28px 58px -32px rgba(15,23,42,0.24);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-form-pane button[type="submit"]:hover,
            .auth-form-pane a.btn-primary:hover,
            .auth-form-pane-inner button[type="submit"]:hover,
            .auth-form-pane-inner a.btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 32px 64px -34px rgba(14,165,233,0.3);
            }

            .auth-form-pane .link, .auth-form-pane-inner .link {
                color: rgba(14,165,233,0.9);
                font-weight: 600;
            }

            .auth-logo {
                width: 48px;
                height: 48px;
            }

            .auth-form-pane-inner {
                max-width: 420px;
                margin: 0 auto;
                display: grid;
                gap: 1.6rem;
            }

            .auth-form-pane-inner h2 {
                font-size: 1.9rem;
                font-weight: 600;
                margin: 0;
            }

            .auth-lead {
                color: rgba(71,85,105,0.78);
                line-height: 1.65;
                margin: 0;
            }

            .auth-form {
                display: grid;
                gap: 1.2rem;
            }

            .auth-form-group {
                display: grid;
                gap: 0.45rem;
            }

            .auth-input {
                width: 100%;
                border-radius: 16px;
                border: 1px solid rgba(148,163,184,0.25);
                background: rgba(255,255,255,0.96);
                padding: 0.85rem 1.1rem;
                font-size: 1rem;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-input:focus {
                outline: none;
                border-color: rgba(14,165,233,0.45);
                box-shadow: 0 0 0 3px rgba(14,165,233,0.18);
            }

            .auth-inline {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .auth-checkbox {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                color: rgba(71,85,105,0.75);
                font-size: 0.9rem;
            }

            .auth-checkbox input {
                width: 16px;
                height: 16px;
                border-radius: 4px;
                accent-color: rgba(14,165,233,0.85);
            }

            .auth-btn {
                width: 100%;
                border-radius: 999px;
                background: linear-gradient(125deg, rgba(14,165,233,0.95), rgba(37,99,235,0.85));
                color: #fff;
                font-weight: 600;
                padding: 0.9rem 1.6rem;
                border: none;
                box-shadow: 0 28px 58px -32px rgba(15,23,42,0.24);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 32px 64px -34px rgba(14,165,233,0.3);
            }

            .auth-btn--ghost {
                background: rgba(255,255,255,0.9);
                color: rgba(15,23,42,0.82);
                border: 1px solid rgba(148,163,184,0.26);
                box-shadow: none;
            }

            .auth-btn--ghost:hover {
                box-shadow: 0 20px 40px -30px rgba(15,23,42,0.18);
            }

            .auth-link {
                color: rgba(14,165,233,0.9);
                font-weight: 600;
                text-decoration: none;
            }

            .auth-link:hover {
                color: rgba(2,132,199,0.95);
            }

            .auth-link--muted {
                font-size: 0.9rem;
            }

            .auth-status {
                background: rgba(14,165,233,0.12);
                border: 1px solid rgba(14,165,233,0.25);
                color: rgba(2,132,199,0.95);
                padding: 0.75rem 1rem;
                border-radius: 18px;
                font-size: 0.92rem;
            }

            .auth-status--info {
                background: rgba(148,163,184,0.15);
                border-color: rgba(148,163,184,0.25);
                color: rgba(71,85,105,0.9);
            }

            .auth-error {
                color: #ef4444;
                font-size: 0.85rem;
            }

            .auth-inline-actions {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: center;
            }

            .auth-inline-actions form {
                flex: 1 1 180px;
            }

            @media (max-width: 480px) {
                .auth-inline-actions form {
                    width: 100%;
                }
            }

            @media (max-width: 960px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                }

                .auth-form-pane {
                    border-left: none;
                    border-top: 1px solid rgba(148,163,184,0.16);
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-shell">
            <div class="auth-info">
                <a href="/" class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark">
                    <x-application-logo class="auth-logo" />
                </a>
                <div>
                    <h1>Run every relationship play with AI precision.</h1>
                    <p>Orchestrate outreach, live chats, and follow-through from one workspace built for MBA operators and network leads.</p>
                </div>
                <div class="auth-testimonials">
                    <div class="auth-quote">
                        “CoffeeChat OS has become the cockpit for our fellowship. Prep decks, live notes, and follow-ups just happen.”
                        <strong>Head of Community · Venture Studio</strong>
                    </div>
                    <div class="auth-quote">
                        “Within six weeks we doubled warm intros and automated the recap workflow. It’s now our default success layer.”
                        <strong>Program Director · MBA Career Center</strong>
                    </div>
                </div>
            </div>
            <div class="auth-form-pane">{{ $slot }}</div>
        </div>
    </body>
</html>

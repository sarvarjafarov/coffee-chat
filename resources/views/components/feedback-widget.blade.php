@php
    $pageTitle = $pageTitle ?? ($seo['title'] ?? ($pageModel->name ?? null ?? null));
    $pagePath = $pagePath ?? request()->path();
@endphp

<div id="cc-feedback-widget" class="cc-feedback-widget">
    <button type="button" class="cc-feedback-toggle" aria-label="Send feedback">
        <span class="mdi mdi-message-text-outline"></span>
        Feedback
    </button>
    <div class="cc-feedback-panel" hidden>
        <div class="cc-feedback-header">
            <div>
                <div class="cc-feedback-title">Tell us what’s off</div>
                <div class="cc-feedback-subtitle">Bugs, confusing steps, or ideas—every note helps.</div>
            </div>
            <button type="button" class="cc-feedback-close" aria-label="Close feedback form">&times;</button>
        </div>
        <form method="POST" action="{{ route('feedback.store') }}" class="cc-feedback-form">
            @csrf
            <input type="hidden" name="page_path" value="{{ $pagePath }}">
            <input type="hidden" name="page_title" value="{{ $pageTitle }}">
            <input type="hidden" name="session_id" data-cc-session-input>
            <div class="cc-field">
                <label for="cc-category">Category</label>
                <select id="cc-category" name="category" class="cc-input">
                    <option value="">Select one</option>
                    <option value="bug">Bug</option>
                    <option value="ux">UX friction</option>
                    <option value="idea">Feature idea</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="cc-field">
                <label for="cc-message">What happened?</label>
                <textarea id="cc-message" name="message" rows="4" class="cc-input" required placeholder="Describe the issue, what you expected, and where it happened."></textarea>
            </div>
            <div class="cc-field">
                <label for="cc-email">Email (optional)</label>
                <input id="cc-email" name="email" type="email" class="cc-input" placeholder="So we can follow up">
            </div>
            <button type="submit" class="cc-submit" data-analytics-event="feedback_submit">Send</button>
            <div class="cc-footnote">We attach page and session info to debug faster.</div>
        </form>
    </div>
</div>

<style>
    .cc-feedback-widget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 2147483000;
        font-family: inherit;
    }
    .cc-feedback-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,0.12);
        background: linear-gradient(135deg, rgba(14,165,233,0.12), rgba(37,99,235,0.12));
        color: #0f172a;
        padding: 0.65rem 1rem;
        font-weight: 600;
        box-shadow: 0 18px 38px -22px rgba(15,23,42,0.35);
    }
    .cc-feedback-panel {
        width: min(360px, 92vw);
        margin-top: 10px;
        padding: 1rem;
        border-radius: 18px;
        background: #fff;
        border: 1px solid rgba(148,163,184,0.26);
        box-shadow: 0 28px 70px -40px rgba(15,23,42,0.35);
    }
    .cc-feedback-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.6rem;
    }
    .cc-feedback-title { font-weight: 700; color: #0f172a; }
    .cc-feedback-subtitle { font-size: 0.9rem; color: rgba(71,85,105,0.85); }
    .cc-feedback-close {
        border: none;
        background: transparent;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        color: rgba(15,23,42,0.5);
    }
    .cc-field { margin-top: 0.8rem; display: grid; gap: 0.25rem; }
    .cc-field label { font-weight: 600; font-size: 0.92rem; color: rgba(71,85,105,0.95); }
    .cc-input {
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(148,163,184,0.28);
        padding: 0.65rem 0.8rem;
        font-size: 0.95rem;
    }
    .cc-input:focus { outline: none; border-color: rgba(14,165,233,0.55); box-shadow: 0 0 0 3px rgba(14,165,233,0.16); }
    .cc-submit {
        margin-top: 0.9rem;
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 0.75rem;
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 18px 40px -24px rgba(37,99,235,0.5);
    }
    .cc-footnote { margin-top: 0.4rem; font-size: 0.8rem; color: rgba(71,85,105,0.7); }
    @media (max-width: 640px) {
        .cc-feedback-widget { right: 12px; bottom: 12px; }
    }
</style>

<script>
    (function() {
        const widget = document.getElementById('cc-feedback-widget');
        if (!widget) return;
        const toggle = widget.querySelector('.cc-feedback-toggle');
        const panel = widget.querySelector('.cc-feedback-panel');
        const closeBtn = widget.querySelector('.cc-feedback-close');
        const sessionInput = widget.querySelector('[data-cc-session-input]');

        // Reuse the analytics session id if present
        try {
            const existing = localStorage.getItem('ccsid');
            if (existing && sessionInput) sessionInput.value = existing;
        } catch (e) {}

        function openPanel() {
            panel.hidden = false;
        }
        function closePanel() {
            panel.hidden = true;
        }

        toggle?.addEventListener('click', openPanel);
        closeBtn?.addEventListener('click', closePanel);
    })();
</script>

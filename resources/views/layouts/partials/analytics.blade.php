@php($analyticsId = config('services.google_analytics.measurement_id'))
@if(! empty($analyticsId))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $analyticsId }}');

        // Lightweight helper to emit custom events from data attributes (click/submit).
        window.ccTrack = function ccTrack(eventName, params = {}) {
            if (typeof gtag !== 'function') return;
            gtag('event', eventName, params);
        };

        document.addEventListener('click', function (e) {
            const el = e.target.closest('[data-analytics-event]');
            if (!el) return;
            const name = el.dataset.analyticsEvent;
            if (!name) return;
            const payload = {};
            Object.keys(el.dataset).forEach((key) => {
                if (key === 'analyticsEvent') return;
                payload[key] = el.dataset[key];
            });
            window.ccTrack(name, payload);
        }, { passive: true });

        document.addEventListener('submit', function (e) {
            const el = e.target;
            if (!el || !el.dataset.analyticsEvent) return;
            const name = el.dataset.analyticsEvent;
            const payload = {};
            Object.keys(el.dataset).forEach((key) => {
                if (key === 'analyticsEvent') return;
                payload[key] = el.dataset[key];
            });
            window.ccTrack(name, payload);
        }, { passive: true });
    </script>
@endif

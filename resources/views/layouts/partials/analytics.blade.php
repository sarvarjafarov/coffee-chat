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

        // Collect selected fields from a form when data-analytics-fields is set.
        function ccCollectFields(formEl) {
            const payload = {};
            const fieldsAttr = formEl?.dataset?.analyticsFields;
            if (!fieldsAttr) return payload;
            const names = fieldsAttr.split(',').map(n => n.trim()).filter(Boolean);

            names.forEach((name) => {
                const nodes = formEl.querySelectorAll(`[name="${name}"]`);
                if (!nodes.length) return;

                if (nodes.length > 1) {
                    const values = [];
                    nodes.forEach((node) => {
                        if ((node.type === 'checkbox' || node.type === 'radio')) {
                            if (node.checked) values.push(node.value);
                        } else {
                            values.push(node.value);
                        }
                    });
                    payload[name] = values;
                    return;
                }

                const node = nodes[0];
                if (node.tagName === 'SELECT' && node.multiple) {
                    payload[name] = Array.from(node.selectedOptions).map(opt => opt.value);
                } else if (node.type === 'checkbox' || node.type === 'radio') {
                    payload[name] = node.checked ? node.value : '';
                } else {
                    payload[name] = node.value;
                }
            });

            return payload;
        }

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
                if (key === 'analyticsFields') return;
                payload[key] = el.dataset[key];
            });
            Object.assign(payload, ccCollectFields(el));
            window.ccTrack(name, payload);
        }, { passive: true });
    </script>
@endif

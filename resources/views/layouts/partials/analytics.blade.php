@php($analyticsId = config('services.google_analytics.measurement_id'))
@if(! empty($analyticsId))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
    <script>
        (function() {
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $analyticsId }}');

            const ANALYTICS_ENDPOINT = '/analytics/events';
            const STORAGE_KEY = 'ccsid';

            function uuid() {
                if (crypto?.randomUUID) return crypto.randomUUID();
                return 'xxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function getSessionId() {
                try {
                    const existing = localStorage.getItem(STORAGE_KEY);
                    if (existing) return existing;
                    const fresh = uuid();
                    localStorage.setItem(STORAGE_KEY, fresh);
                    return fresh;
                } catch (e) {
                    return uuid();
                }
            }

            const sessionId = getSessionId();

            function ccSend(eventName, params = {}) {
                if (!eventName || !sessionId) return;
                const payload = {
                    event_name: eventName,
                    session_id: sessionId,
                    properties: params,
                    path: window.location.pathname,
                    referrer: document.referrer || null,
                    occurred_at: new Date().toISOString(),
                };

                const body = JSON.stringify(payload);
                if (navigator.sendBeacon) {
                    try {
                        const blob = new Blob([body], { type: 'application/json' });
                        navigator.sendBeacon(ANALYTICS_ENDPOINT, blob);
                        return;
                    } catch (e) { /* fall through */ }
                }

                try {
                    fetch(ANALYTICS_ENDPOINT, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body,
                        credentials: 'include',
                    });
                } catch (e) {
                    // Swallow client-side analytics errors.
                }
            }

            // Lightweight helper to emit custom events from data attributes (click/submit) and persist server-side.
            window.ccTrack = function ccTrack(eventName, params = {}) {
                if (typeof gtag === 'function') {
                    gtag('event', eventName, params);
                }
                ccSend(eventName, params);
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

            // Fire initial touchpoint with UTM/referrer context.
            (function sendInitialTouchpoint() {
                const params = {};
                const search = new URLSearchParams(window.location.search);
                ['utm_source','utm_medium','utm_campaign','utm_content','utm_term'].forEach((key) => {
                    const val = search.get(key);
                    if (val) params[key] = val;
                });
                if (document.referrer) params.referrer = document.referrer;
                params.landing_page = window.location.pathname + window.location.search;

                if (Object.keys(params).length > 0) {
                    ccSend('touchpoint', params);
                }
            })();
        })();
    </script>
@endif

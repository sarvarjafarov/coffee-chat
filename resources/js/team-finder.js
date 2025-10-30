const initTeamFinder = () => {
    const form = document.querySelector('[data-team-finder-form]');

    if (!form) {
        return;
    }

    const endpoint = form.dataset.searchEndpoint;
    const followAction = form.dataset.followAction;
    const resultsWrapper = document.querySelector('[data-team-finder-results-wrapper]');
    const topWrapper = document.querySelector('[data-team-finder-top-wrapper]');
    const diagnosticsWrapper = document.querySelector('[data-team-finder-diagnostics-wrapper]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const messageContainer = resultsWrapper?.querySelector('[data-team-finder-message]');

    const renderMessage = (type, text) => {
        if (!messageContainer) {
            return;
        }

        if (!text) {
            messageContainer.innerHTML = '';
            return;
        }

        const alertClass = type === 'error'
            ? 'alert-danger'
            : type === 'warning'
                ? 'alert-warning'
                : 'alert-info';

        messageContainer.innerHTML = `<div class="alert ${alertClass} mb-0">${text}</div>`;
    };

    const renderPlaceholder = (variant) => {
        const placeholder = resultsWrapper?.querySelector('[data-team-finder-placeholder]');
        if (!resultsWrapper) {
            return;
        }

        resultsWrapper.querySelectorAll('[data-team-finder-placeholder]').forEach((el) => el.remove());

        if (!variant) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'team-finder-empty text-center py-5';
        wrapper.setAttribute('data-team-finder-placeholder', '');

        if (variant === 'loading') {
            wrapper.innerHTML = `
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h2 class="h5">Gathering leads...</h2>
                <p class="text-subtle mb-0">We’re sourcing potential teammates in real-time. Stay on the page and we’ll drop them in below.</p>
            `;
        } else if (variant === 'empty') {
            wrapper.innerHTML = `
                <h2 class="h5">No leads yet</h2>
                <p class="text-subtle mb-0">We couldn’t find anyone matching those filters. Adjust the search or try again later.</p>
            `;
        } else if (variant === 'start') {
            wrapper.innerHTML = `
                <h2 class="h5">Start with a company &amp; role</h2>
                <p class="text-subtle mb-0">Enter a company and position to uncover warm coffee-chat prospects from across the web.</p>
            `;
        }

        const footnote = resultsWrapper?.querySelector('[data-team-finder-footnote]');
        if (footnote) {
            resultsWrapper.insertBefore(wrapper, footnote);
        } else {
            resultsWrapper.appendChild(wrapper);
        }
    };

    const ensureResultsGrid = () => {
        if (!resultsWrapper) {
            return null;
        }

        let grid = resultsWrapper.querySelector('[data-team-finder-results]');
        if (!grid) {
            grid = document.createElement('div');
            grid.className = 'row g-4';
            grid.setAttribute('data-team-finder-results', '');
            resultsWrapper.appendChild(grid);
        }

        return grid;
    };

    const renderTopMatches = (recommended, summary, query = {}) => {
        if (!topWrapper) {
            return;
        }

        topWrapper.innerHTML = '';

        if (!recommended.length) {
            return;
        }

        const summaryText = (() => {
            if (!summary || !summary.total) {
                return '';
            }
            const sources = summary.by_source
                ? Object.keys(summary.by_source).map((label) => label.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()))
                : [];
            if (!sources.length) {
                return `${summary.total} total matches surfaced.`;
            }

            if (sources.length === 1) {
                return `${summary.total} total matches surfaced from ${sources[0]}.`;
            }

            const last = sources.pop();
            return `${summary.total} total matches surfaced from ${sources.join(', ')} and ${last}.`;
        })();

        const card = document.createElement('div');
        card.className = 'workspace-card';
        card.innerHTML = `
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="workspace-eyebrow text-uppercase">Top matches</span>
                    <h2 class="h5 mb-1">Best prospects to reach out to first</h2>
                    ${summaryText ? `<p class="text-subtle mb-0">${summaryText}</p>` : ''}
                </div>
            </div>
            <div class="row g-3 mt-3" data-team-finder-top-list></div>
        `;

        const list = card.querySelector('[data-team-finder-top-list]');

        recommended.forEach((item) => {
            const column = document.createElement('div');
            column.className = 'col-md-6 col-xl-4';
            column.innerHTML = buildCardHtml(item, query);
            list.appendChild(column);
        });

        topWrapper.appendChild(card);
    };

    const renderResults = (results, query, summary) => {
        const grid = ensureResultsGrid();
        if (!grid) {
            return;
        }

        grid.innerHTML = '';

        if (!results.length) {
            grid.remove();
            renderPlaceholder('empty');
            return;
        }

        results.forEach((result) => {
            const column = document.createElement('div');
            column.className = 'col-md-6 col-xl-4';
            column.innerHTML = buildCardHtml(result, query);
            grid.appendChild(column);
        });
    };

    const submitButton = form.querySelector('button[type="submit"]');

    const setLoading = (loading) => {
        if (submitButton) {
            submitButton.disabled = loading;
            submitButton.classList.toggle('disabled', loading);
        }

        if (loading) {
            renderPlaceholder('loading');
        }
    };

    const existingResults = resultsWrapper?.querySelector('[data-team-finder-results]');
    if (!existingResults || !existingResults.children.length) {
        renderPlaceholder('start');
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!endpoint) {
            renderMessage('error', 'Search endpoint is not configured.');
            return;
        }

        const formData = new FormData(form);
        const company = String(formData.get('company') ?? '').trim();
        const position = String(formData.get('position') ?? '').trim();
        const team = String(formData.get('team') ?? formData.get('team_name') ?? '').trim();
        const city = String(formData.get('city') ?? '').trim();

        if (!company || !position) {
            renderMessage('warning', 'Please provide both a company and a position to discover coffee chat matches.');
            renderPlaceholder('start');
            return;
        }

        renderMessage(null, '');
        setLoading(true);

        try {
            const url = new URL(endpoint, window.location.origin);
            url.searchParams.set('company', company);
            url.searchParams.set('position', position);
            if (team) {
                url.searchParams.set('team', team);
            }
            if (city) {
                url.searchParams.set('city', city);
            }

            const response = await fetch(url.toString(), {
                headers: {
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error(`Search failed with status ${response.status}`);
            }

            const payload = await response.json();
            const results = Array.isArray(payload.results) ? payload.results : [];
            const recommended = Array.isArray(payload.recommended) ? payload.recommended : [];
            const summary = payload.summary ?? {};

            renderPlaceholder(null);
            renderTopMatches(recommended, summary, {
                company,
                position,
                team,
                city,
            });
            renderResults(results, {
                company,
                position,
                team,
                city,
            }, summary);
            renderDiagnostics(Array.isArray(payload.diagnostics) ? payload.diagnostics : []);

            if (!results.length) {
                renderMessage('info', 'No matches yet. Try refining the company or position keywords.');
            } else {
                renderMessage(null, '');
            }
        } catch (error) {
            console.error(error);
            renderPlaceholder('empty');
            renderTopMatches([], {}, {
                company,
                position,
                team,
                city,
            });
            renderMessage('error', 'We couldn’t reach the discovery service. Try again in a moment.');
        } finally {
            setLoading(false);
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTeamFinder);
} else {
    initTeamFinder();
}
    const renderDiagnostics = (diagnostics) => {
        if (!diagnosticsWrapper) {
            return;
        }

        diagnosticsWrapper.innerHTML = '';

        if (!diagnostics || !diagnostics.length) {
            return;
        }

        const alert = document.createElement('div');
        alert.className = 'alert alert-info mb-0';

        let listItems = '';
        diagnostics.forEach((diag) => {
            const sourceLabel = (diag.source || 'unknown').replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
            let message = '';
            if (diag.status === 'success') {
                message = `${diag.count || 0} match(es) returned.`;
            } else if (diag.status === 'no_results') {
                message = 'No matches returned for this query.';
            } else {
                message = diag.message || 'Request failed.';
            }
            listItems += `<li><span class="fw-semibold">${sourceLabel}:</span> ${message}</li>`;
        });

        alert.innerHTML = `
            <strong>Provider status</strong>
            <ul class="mb-0 mt-2 ps-3">
                ${listItems}
            </ul>
        `;

        diagnosticsWrapper.appendChild(alert);
    };
    const encodePayload = (payload) => {
        try {
            return JSON.stringify(payload)
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        } catch (error) {
            return '';
        }
    };

    const escapeHtml = (value) => {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    const truncateText = (value, limit = 220) => {
        if (!value) {
            return '';
        }

        const plain = String(value);
        return plain.length > limit ? `${plain.slice(0, limit - 1)}…` : plain;
    };

    const buildCardHtml = (item, query = {}) => {
        const name = escapeHtml(item.name ?? 'Potential contact');
        const company = escapeHtml(item.company ?? query.company ?? 'Unknown company');
        const role = escapeHtml(item.role ?? '');
        const team = escapeHtml(item.team ?? '');
        const location = escapeHtml(item.location ?? '');
        const sourceLabelRaw = item.source ? String(item.source).replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()) : null;
        const sourceLabel = sourceLabelRaw ? escapeHtml(sourceLabelRaw) : null;
        const confidence = typeof item.confidence === 'number'
            ? Math.round(item.confidence * 100)
            : null;
        const snippetValue = truncateText(item.primary_reason || item.snippet || '', 220);
        const snippet = snippetValue ? escapeHtml(snippetValue) : '';
        const url = item.url || item.profile_url || '';
        const urlEscaped = escapeHtml(url);
        const payload = encodePayload(item);
        const noteData = JSON.stringify(item).replace(/'/g, '&#39;');

        return `
            <div class="team-finder-result h-100">
                <div>
                    <h3 class="mb-1">${name}</h3>
                    <div class="team-finder-meta">
                        <span>
                            ${company}${role ? ` · ${role}` : ''}
                        </span>
                        ${team ? `<span>${team}</span>` : ''}
                        ${location ? `<span>${location}</span>` : ''}
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            ${sourceLabel ? `
                                <span class="team-finder-chip team-finder-chip--source">
                                    <i class="mdi mdi-radar"></i>
                                    ${sourceLabel}
                                </span>` : ''}
                            ${confidence !== null ? `
                                <span class="team-finder-chip team-finder-chip--confidence">
                                    <i class="mdi mdi-target"></i>
                                    ${confidence}% confidence
                                </span>` : ''}
                        </div>
                    </div>
                </div>
                <div class="team-finder-actions d-flex flex-column gap-3 mt-auto">
                    ${snippet ? `<p class="mb-0 text-sm text-slate-600">${snippet}</p>` : ''}
                    <div class="d-flex flex-wrap gap-2">
                        ${url ? `
                            <a href="${urlEscaped}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                <span class="mdi mdi-open-in-new"></span>
                                View profile
                            </a>` : ''}
                        ${followAction && csrfToken && payload ? `
                            <form action="${followAction}" method="POST" class="d-inline" data-follow-coffee-chat>
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="contact" value="${payload}">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <span class="mdi mdi-send"></span>
                                    Follow for coffee chat
                                </button>
                            </form>` : ''}
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-result='${noteData}'>
                            <span class="mdi mdi-note-plus-outline"></span>
                            Add note
                        </button>
                    </div>
                </div>
            </div>
        `;
    };

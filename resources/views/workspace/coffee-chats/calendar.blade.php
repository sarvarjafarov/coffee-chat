@extends('workspace.layout')

@section('workspace-content')
    @php
        $events = collect($events ?? []);
    @endphp

    <style>
        #calendar-wrapper {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            gap: clamp(1.4rem, 3vw, 2rem);
            align-items: flex-start;
            width: 100%;
        }

        #calendar {
            background: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,251,255,0.95) 100%);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 28px;
            padding: clamp(1rem, 3vw, 1.4rem);
            box-shadow: 0 34px 80px -54px rgba(15,23,42,0.24);
        }

        .fc-theme-standard .fc-scrollgrid {
            border: none;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1.4rem;
        }

        .fc .fc-toolbar-title {
            color: rgba(15,23,42,0.92);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .fc .fc-button-primary {
            background: rgba(14,165,233,0.16);
            border: 1px solid rgba(14,165,233,0.3);
            color: var(--accent-strong);
            border-radius: 999px;
            padding: 0.45rem 0.95rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:hover {
            background: rgba(14,165,233,0.26);
            border-color: rgba(14,165,233,0.42);
            box-shadow: 0 14px 30px -18px rgba(14,165,233,0.35);
        }

        .fc-daygrid-event {
            background: linear-gradient(135deg, rgba(59,130,246,0.92), rgba(37,99,235,0.82));
            border-radius: 10px;
            border: none;
            font-weight: 600;
            padding: 0.2rem 0.45rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            color: #f8fafc;
        }

        .fc-daygrid-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -18px rgba(14,165,233,0.35);
        }

        .fc-daygrid-event.selected-event {
            box-shadow: 0 16px 32px -18px rgba(14,165,233,0.4);
            transform: translateY(-1px);
        }

        .calendar-detail {
            order: 2;
            width: clamp(280px, 30vw, 360px);
            border-radius: 24px;
            background: rgba(255,255,255,0.98);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 36px 90px -58px rgba(15,23,42,0.28);
            padding: clamp(1.4rem, 3vw, 1.8rem);
            display: none;
            flex-direction: column;
            gap: 1rem;
            backdrop-filter: blur(14px);
            animation: calendarDetailIn 0.25s ease forwards;
        }

        .calendar-detail.is-visible {
            display: flex;
        }

        .calendar-detail-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .calendar-detail-close {
            border: none;
            background: rgba(15,23,42,0.06);
            color: rgba(15,23,42,0.75);
            border-radius: 999px;
            padding: 0.25rem 0.7rem;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .calendar-detail h3 {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .calendar-detail small,
        .calendar-detail .text-subtle {
            color: rgba(71,85,105,0.78);
        }

        .calendar-detail .chip {
            align-self: flex-start;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            border: 1px solid rgba(14,165,233,0.25);
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.85);
            font-weight: 600;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
        }

        .calendar-detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .calendar-detail-actions .btn {
            border-radius: 999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }

        .calendar-detail-row {
            display: flex;
            gap: 0.65rem;
            align-items: flex-start;
        }

        .calendar-detail-row .icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(14,165,233,0.12);
            color: rgba(14,165,233,0.7);
            display: grid;
            place-items: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        @media (max-width: 992px) {
            #calendar {
                min-width: 100%;
            }
            .calendar-detail {
                width: 100%;
                order: 2;
            }
        }

        @keyframes calendarDetailIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">

    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Calendar</span>
            <h1>Coffee chat calendar</h1>
            <p class="text-subtle">Visualise upcoming chats and drill into details directly from the grid.</p>
        </div>
    </div>

    <div class="workspace-section" id="calendar-wrapper">
        <div id="calendar"></div>

        <aside class="calendar-detail" id="calendar-detail">
            <div class="calendar-detail-head">
                <span class="chip text-uppercase" id="calendar-detail-status">Scheduled</span>
                <button type="button" class="calendar-detail-close" id="calendar-detail-close">Close</button>
            </div>
            <div>
                <h3 id="calendar-detail-title">Select a chat</h3>
                <small id="calendar-detail-datetime"></small>
            </div>
            <div class="calendar-detail-row" id="calendar-detail-location-row" style="display:none;">
                <span class="icon mdi mdi-map-marker-outline"></span>
                <div class="text-subtle" id="calendar-detail-location"></div>
            </div>
            <div class="calendar-detail-row" id="calendar-detail-notes-row" style="display:none;">
                <span class="icon mdi mdi-text-subject"></span>
                <div class="text-subtle" id="calendar-detail-notes"></div>
            </div>
            <div class="calendar-detail-actions">
                <a href="#" class="btn btn-outline-primary btn-sm" id="calendar-detail-ics" target="_blank">
                    <span class="mdi mdi-calendar-arrow-right me-1"></span>
                    Apple / Outlook
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm" id="calendar-detail-google" target="_blank">
                    <span class="mdi mdi-google me-1"></span>
                    Google Calendar
                </a>
                <a href="#" class="btn btn-primary btn-sm" id="calendar-detail-edit">
                    <span class="mdi mdi-pencil-outline me-1"></span>
                    Edit chat
                </a>
            </div>
        </aside>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const detailPanel = document.getElementById('calendar-detail');
            const detailClose = document.getElementById('calendar-detail-close');
            const detailTitle = document.getElementById('calendar-detail-title');
            const detailDatetime = document.getElementById('calendar-detail-datetime');
            const detailLocationRow = document.getElementById('calendar-detail-location-row');
            const detailLocation = document.getElementById('calendar-detail-location');
            const detailNotesRow = document.getElementById('calendar-detail-notes-row');
            const detailNotes = document.getElementById('calendar-detail-notes');
            const detailStatus = document.getElementById('calendar-detail-status');
            const detailIcs = document.getElementById('calendar-detail-ics');
            const detailGoogle = document.getElementById('calendar-detail-google');
            const detailEdit = document.getElementById('calendar-detail-edit');
            let previouslySelected;

            const events = @json($events);

            const eventLookup = events.reduce((map, event) => {
                map[event.id] = event;
                return map;
            }, {});

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: events.map(event => ({
                    id: event.id,
                    title: event.title,
                    start: event.start,
                    extendedProps: {
                        status: event.status,
                        location: event.location,
                        notes: event.notes,
                        time_zone: event.time_zone,
                        scheduled_at: event.start,
                        ics_url: event.ics_url,
                        google_url: event.google_url,
                        edit_url: event.edit_url,
                    }
                })),
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const data = eventLookup[info.event.id] || info.event.extendedProps || {};
                    const start = new Date(info.event.start);

                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected-event');
                    }
                    info.el.classList.add('selected-event');
                    previouslySelected = info.el;

                    detailTitle.textContent = info.event.title || 'Coffee chat';
                    detailDatetime.textContent = start
                        ? start.toLocaleString(undefined, {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                        }) + (data.time_zone ? ` ${data.time_zone}` : '')
                        : '';

                    if (data.location) {
                        detailLocationRow.style.display = 'flex';
                        detailLocation.textContent = data.location;
                    } else {
                        detailLocationRow.style.display = 'none';
                    }

                    if (data.notes) {
                        detailNotesRow.style.display = 'flex';
                        detailNotes.textContent = data.notes;
                    } else {
                        detailNotesRow.style.display = 'none';
                    }

                    if (data.status) {
                        detailStatus.style.display = 'inline-flex';
                        detailStatus.textContent = data.status;
                    } else {
                        detailStatus.style.display = 'none';
                    }

                    if (data.ics_url) {
                        detailIcs.href = data.ics_url;
                        detailIcs.style.display = 'inline-flex';
                    } else {
                        detailIcs.style.display = 'none';
                    }

                    if (data.google_url) {
                        detailGoogle.href = data.google_url;
                        detailGoogle.style.display = 'inline-flex';
                    } else {
                        detailGoogle.style.display = 'none';
                    }

                    if (data.edit_url) {
                        detailEdit.href = data.edit_url;
                        detailEdit.style.display = 'inline-flex';
                    } else {
                        detailEdit.style.display = 'none';
                    }

                    detailPanel.classList.add('is-visible');
                    if (window.innerWidth <= 992) {
                        detailPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            });

            detailClose.addEventListener('click', () => {
                detailPanel.classList.remove('is-visible');
                if (previouslySelected) {
                    previouslySelected.classList.remove('selected-event');
                    previouslySelected = null;
                }
                detailTitle.textContent = 'Select a chat';
                detailDatetime.textContent = '';
                detailLocationRow.style.display = 'none';
                detailNotesRow.style.display = 'none';
                detailStatus.style.display = 'none';
                detailIcs.style.display = 'none';
                detailGoogle.style.display = 'none';
                detailEdit.style.display = 'none';
            });

            calendar.render();
        });
    </script>
@endsection

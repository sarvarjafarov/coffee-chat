@php
    $chat = $chat ?? null;
    $dynamicFields = $dynamicFields ?? collect();
    $extras = $chat?->extras ?? [];
@endphp
<style>
    .workspace-form-section-block {
        margin-bottom: 0.6rem;
    }
    .form-section-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.35rem;
    }
    .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.7rem;
        border-radius: 999px;
        background: rgba(14,165,233,0.12);
        color: rgba(14,165,233,0.9);
        font-size: 0.7rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .section-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .pill-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }
    .pill {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.62rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(148,163,184,0.28);
        background: rgba(255,255,255,0.96);
        color: var(--text-primary);
        font-weight: 600;
        cursor: pointer;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.9), 0 12px 28px -22px rgba(15,23,42,0.35);
        transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease, color 0.18s ease, transform 0.18s ease;
    }
    .pill input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .pill:focus-within {
        box-shadow: 0 0 0 3px rgba(14,165,233,0.16), inset 0 1px 0 rgba(255,255,255,0.9);
    }
    .pill.is-selected {
        border-color: rgba(14,165,233,0.48);
        background: linear-gradient(135deg, rgba(236,249,255,0.98), rgba(214,241,255,0.92));
        color: rgba(15,23,42,0.95);
        box-shadow: 0 14px 36px -24px rgba(14,165,233,0.55);
        transform: translateY(-1px);
    }
    .pill.is-selected .pill-dot {
        background: rgba(14,165,233,0.85);
    }
    .pill-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(148,163,184,0.6);
    }
    .pill-group--tight .pill {
        padding: 0.5rem 0.8rem;
    }
    .textarea-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        margin-top: 0.35rem;
    }
    .textarea-meta small {
        margin: 0;
    }
    .soft-fade {
        opacity: 0.82;
    }
</style>

<div class="workspace-form-section-block">
    <div class="form-section-heading">
        <div>
            <div class="section-eyebrow">Conversation</div>
            <p class="section-title mb-1">Status & logistics</p>
            <p class="text-subtle small mb-0" data-status-hint>Keep status, time, and rating front-and-center.</p>
        </div>
        <span class="text-subtle small">Mark a chat Completed when you are ready to rate and summarize.</span>
    </div>

    <div class="row g-3 align-items-end">
        <div class="col-lg-5">
            <label class="form-label">Status</label>
            <div class="pill-group" data-pill-group>
                @foreach($statusOptions as $value => $label)
                    <label class="pill">
                        <input type="radio" name="status" value="{{ $value }}" @checked(old('status', $chat->status) === $value) required>
                        <span class="pill-dot" aria-hidden="true"></span>
                        <span class="pill-label">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <small class="text-subtle">Planned vs. Completed drives which follow-ups matter.</small>
        </div>
        <div class="col-lg-4">
            <label class="form-label">Scheduled at</label>
            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional($chat->scheduled_at)->format('Y-m-d\TH:i')) }}">
            <small class="text-subtle">Add date and time; we’ll validate on blur.</small>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Time zone</label>
            <input type="text" name="time_zone" class="form-control" value="{{ old('time_zone', $chat->time_zone) }}" placeholder="Auto-detect or type (e.g. PST)">
            <small class="text-subtle">Defaults to your profile time zone.</small>
        </div>
        <div class="col-md-3">
            <label class="form-label d-flex justify-content-between">
                <span>Duration (minutes)</span>
                <span class="text-subtle small">5–480</span>
            </label>
            <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $chat->duration_minutes) }}" min="5" max="480">
        </div>
        <div class="col-md-4">
            <label class="form-label d-flex justify-content-between">
                <span>Rating (1-5)</span>
                <span class="text-subtle small">1 = not helpful, 5 = great</span>
            </label>
            @php($currentRating = (int) old('rating', $chat->rating))
            <div class="pill-group pill-group--tight" data-pill-group data-rating-group>
                @for($i = 1; $i <= 5; $i++)
                    <label class="pill">
                        <input type="radio" name="rating" value="{{ $i }}" @checked($currentRating === $i)>
                        <span class="pill-label">{{ $i }}</span>
                    </label>
                @endfor
            </div>
            <small class="text-subtle">Add when the chat is complete.</small>
        </div>
        <div class="col-md-5">
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="is_virtual" value="1" id="is_virtual" @checked(old('is_virtual', $chat->is_virtual))>
                <label class="form-check-label" for="is_virtual">Virtual meeting</label>
            </div>
            <small class="text-subtle">Toggle off for in-person chats.</small>
        </div>
        <div class="col-md-7">
            <label class="form-label" data-location-label>Location / link</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $chat->location) }}" data-location-input>
            <small class="text-subtle" data-location-helper>Share a Meet/Zoom link or a short venue name.</small>
        </div>
    </div>
</div>

<div class="workspace-divider"></div>

<div class="workspace-form-section-block">
    <div class="form-section-heading">
        <div>
            <div class="section-eyebrow">People</div>
            <p class="section-title mb-1">Contacts & focus</p>
            <p class="text-subtle small mb-0">Keep who you spoke with and where you met in one row.</p>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Company</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $chat->company->name ?? '') }}" placeholder="e.g. Stripe">
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact name</label>
            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $chat->contact->name ?? '') }}" placeholder="Person you met">
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact email</label>
            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $chat->contact->email ?? '') }}" placeholder="Optional">
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact role</label>
            <input type="text" name="contact_position" class="form-control" value="{{ old('contact_position', $chat->contact->position ?? '') }}" placeholder="Optional">
        </div>
        <div class="col-md-6">
            <label class="form-label">Role / topic</label>
            <input type="text" name="position_title" class="form-control" value="{{ old('position_title', $chat->position_title) }}" placeholder="Product Manager, SWE intern...">
            <small class="text-subtle">What was the role or topic you focused on?</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">Channels</label>
            @php
                $selectedChannels = old('channels', optional($chat->channels)->pluck('id')->all() ?? []);
            @endphp
            <div class="pill-group" data-pill-group>
                @foreach($channels as $channel)
                    <label class="pill">
                        <input type="checkbox" name="channels[]" value="{{ $channel->id }}" @checked(in_array($channel->id, $selectedChannels))>
                        <span class="pill-dot" aria-hidden="true"></span>
                        <span class="pill-label">{{ $channel->label }}</span>
                    </label>
                @endforeach
            </div>
            <small class="text-subtle">Select all that applied—no more Cmd/Ctrl instructions.</small>
        </div>
    </div>
</div>

<div class="workspace-divider"></div>

<div class="workspace-form-section-block">
    <div class="form-section-heading">
        <div>
            <div class="section-eyebrow">Notes</div>
            <p class="section-title mb-1">Notes & follow-up</p>
            <p class="text-subtle small mb-0">Add a quick summary, takeaways, and next steps with light guidance.</p>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Summary</label>
            <div data-textarea-wrapper>
                <textarea name="summary" rows="3" class="form-control" data-max="320">{{ old('summary', $chat->summary) }}</textarea>
                <div class="textarea-meta">
                    <small class="text-subtle">What stood out in the conversation?</small>
                    <small class="text-subtle" data-char-counter></small>
                </div>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Key takeaways</label>
            <div data-textarea-wrapper>
                <textarea name="key_takeaways" rows="3" class="form-control" data-max="320">{{ old('key_takeaways', $chat->key_takeaways) }}</textarea>
                <div class="textarea-meta">
                    <small class="text-subtle">Insights about the role, team, or company culture.</small>
                    <small class="text-subtle" data-char-counter></small>
                </div>
            </div>
        </div>
        <div class="col-12" data-show-when-completed>
            <label class="form-label">Next steps</label>
            <div data-textarea-wrapper>
                <textarea name="next_steps" rows="3" class="form-control" placeholder="Action + owner + due by (e.g., Send thank-you note by tomorrow)" data-max="260">{{ old('next_steps', $chat->next_steps) }}</textarea>
                <div class="textarea-meta">
                    <small class="text-subtle">Keep it actionable: what, who, and by when.</small>
                    <small class="text-subtle" data-char-counter></small>
                </div>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Internal notes</label>
            <div data-textarea-wrapper>
                <textarea name="notes" rows="4" class="form-control" placeholder="Visible only to you" data-max="380">{{ old('notes', $chat->notes) }}</textarea>
                <div class="textarea-meta">
                    <small class="text-subtle">Use this for sourcing details or things you promised.</small>
                    <small class="text-subtle" data-char-counter></small>
                </div>
            </div>
        </div>
    </div>
</div>

@if($dynamicFields->isNotEmpty())
    <div class="workspace-divider"></div>
    <div class="workspace-form-section-block">
        <div class="form-section-heading">
            <div>
                <div class="section-eyebrow">Additional details</div>
                <p class="section-title mb-1">Contextual tags</p>
                <p class="text-subtle small mb-0">Add any extra fields your workspace configured.</p>
            </div>
        </div>
    <div class="row g-3">
        @foreach($dynamicFields as $field)
            @php
                $name = 'field_' . $field->key;
                $current = old($name, data_get($extras, $field->key));
                $columnSpan = (int) data_get($field->style, 'column_span', 6);
                $columnSpan = $columnSpan > 0 ? $columnSpan : 6;
                $options = collect($field->options ?? [])->map(function ($option) {
                    if (is_array($option)) {
                        return [
                            'value' => $option['value'] ?? '',
                            'label' => $option['label'] ?? ($option['value'] ?? ''),
                        ];
                    }

                    return ['value' => $option, 'label' => $option];
                });
            @endphp
            <div class="col-md-{{ $columnSpan }}">
                <div class="form-group">
                    <label class="form-label">{{ $field->label }} @if($field->required)<span class="text-danger">*</span>@endif</label>
                    @if($field->help_text)
                        <p class="text-subtle small mb-1">{{ $field->help_text }}</p>
                    @endif

                    @switch($field->type)
                        @case('textarea')
                            <textarea name="{{ $name }}" rows="3" class="form-control" placeholder="{{ $field->placeholder }}">{{ is_array($current) ? implode(', ', $current) : $current }}</textarea>
                            @break

                        @case('number')
                            <input type="number" name="{{ $name }}" class="form-control" value="{{ $current }}" placeholder="{{ $field->placeholder }}">
                            @break

                        @case('date')
                            <input type="date" name="{{ $name }}" class="form-control" value="{{ $current }}">
                            @break

                        @case('datetime')
                            <input type="datetime-local" name="{{ $name }}" class="form-control" value="{{ $current ? \Illuminate\Support\Carbon::parse($current)->format('Y-m-d\TH:i') : '' }}">
                            @break

                        @case('boolean')
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" @checked($current)>
                                <label class="form-check-label" for="{{ $name }}">{{ $field->placeholder ?? 'Yes' }}</label>
                            </div>
                            @break

                        @case('select')
                            <select name="{{ $name }}" class="form-select">
                                <option value="">— Select —</option>
                                @foreach($options as $option)
                                    <option value="{{ $option['value'] }}" @selected($current == $option['value'])>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                            @break

                        @case('multiselect')
                            @php($current = is_array($current) ? $current : (array) $current)
                            <div class="pill-group" data-pill-group>
                                @foreach($options as $option)
                                    <label class="pill">
                                        <input type="checkbox" name="{{ $name }}[]" value="{{ $option['value'] }}" @checked(in_array($option['value'], $current ?? []))>
                                        <span class="pill-dot" aria-hidden="true"></span>
                                        <span class="pill-label">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <small class="text-subtle">Select all that apply.</small>
                            @break

                        @default
                            <input type="text" name="{{ $name }}" class="form-control" value="{{ is_array($current) ? implode(', ', $current) : $current }}" placeholder="{{ $field->placeholder }}">
                    @endswitch
                    @error($name)
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error($name . '.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
    </div>
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pillGroups = document.querySelectorAll('[data-pill-group]');
            pillGroups.forEach(function (group) {
                const inputs = group.querySelectorAll('input[type="checkbox"], input[type="radio"]');

                const sync = function () {
                    inputs.forEach(function (input) {
                        const label = input.closest('label');
                        if (!label) {
                            return;
                        }
                        label.classList.toggle('is-selected', input.checked);
                        label.setAttribute('aria-pressed', input.checked ? 'true' : 'false');
                    });
                };

                group.addEventListener('change', sync);
                sync();
            });

            const textareaWrappers = document.querySelectorAll('[data-textarea-wrapper]');
            textareaWrappers.forEach(function (wrapper) {
                const textarea = wrapper.querySelector('textarea');
                const counter = wrapper.querySelector('[data-char-counter]');
                if (! textarea || ! counter) {
                    return;
                }

                const max = parseInt(textarea.dataset.max || '0', 10);
                const updateCount = function () {
                    const count = textarea.value.length;
                    if (max) {
                        counter.textContent = count + '/' + max;
                        counter.classList.toggle('text-danger', count > max);
                    } else {
                        counter.textContent = count + ' chars';
                    }
                };

                textarea.addEventListener('input', updateCount);
                updateCount();
            });

            const virtualToggle = document.querySelector('#is_virtual');
            const locationLabel = document.querySelector('[data-location-label]');
            const locationInput = document.querySelector('[data-location-input]');
            const locationHelper = document.querySelector('[data-location-helper]');

            const syncLocationCopy = function () {
                if (! locationLabel || ! locationInput || ! virtualToggle) {
                    return;
                }
                if (virtualToggle.checked) {
                    locationLabel.textContent = 'Meeting link';
                    locationInput.placeholder = 'e.g. Zoom, Meet, Teams link';
                    if (locationHelper) {
                        locationHelper.textContent = 'Paste the link you used to meet.';
                    }
                } else {
                    locationLabel.textContent = 'Location';
                    locationInput.placeholder = 'e.g. Cafe, office, campus';
                    if (locationHelper) {
                        locationHelper.textContent = 'Add a quick venue or room to find it later.';
                    }
                }
            };

            virtualToggle?.addEventListener('change', syncLocationCopy);
            syncLocationCopy();
        });
    </script>
@endpush

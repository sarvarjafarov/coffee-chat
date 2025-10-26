@php
    $chat = $chat ?? null;
    $dynamicFields = $dynamicFields ?? collect();
    $extras = $chat?->extras ?? [];
@endphp

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
    <div class="col-md-4">
        <label class="form-label">Scheduled at</label>
        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional($chat->scheduled_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Time zone</label>
        <input type="text" name="time_zone" class="form-control" value="{{ old('time_zone', $chat->time_zone) }}" placeholder="e.g. PST">
    </div>
    <div class="col-md-4">
        <label class="form-label">Location / link</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $chat->location) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $chat->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $chat->duration_minutes) }}" min="5" max="480">
    </div>
    <div class="col-md-4">
        <label class="form-label">Rating (1-5)</label>
        <input type="number" name="rating" class="form-control" value="{{ old('rating', $chat->rating) }}" min="1" max="5">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_virtual" value="1" id="is_virtual" @checked(old('is_virtual', $chat->is_virtual))>
            <label class="form-check-label" for="is_virtual">Virtual meeting</label>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Role / topic</label>
        <input type="text" name="position_title" class="form-control" value="{{ old('position_title', $chat->position_title) }}" placeholder="Product Manager, SWE intern...">
    </div>
    <div class="col-md-6">
        <label class="form-label">Channels</label>
        @php
            $selectedChannels = old('channels', optional($chat->channels)->pluck('id')->all() ?? []);
        @endphp
        <select name="channels[]" class="form-select" multiple>
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}" @selected(in_array($channel->id, $selectedChannels))>{{ $channel->label }}</option>
            @endforeach
        </select>
        <small class="text-subtle">Hold Cmd/Ctrl to select multiple.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Summary</label>
        <textarea name="summary" rows="3" class="form-control">{{ old('summary', $chat->summary) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Key takeaways</label>
        <textarea name="key_takeaways" rows="3" class="form-control">{{ old('key_takeaways', $chat->key_takeaways) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Next steps</label>
        <textarea name="next_steps" rows="3" class="form-control">{{ old('next_steps', $chat->next_steps) }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Internal notes</label>
        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $chat->notes) }}</textarea>
    </div>
</div>

@if($dynamicFields->isNotEmpty())
    <hr class="my-4 border-light border-opacity-25">
    <h4 class="h5 text-white mb-3">Additional details</h4>
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
                            <select name="{{ $name }}[]" class="form-select" multiple>
                                @foreach($options as $option)
                                    <option value="{{ $option['value'] }}" @selected(in_array($option['value'], $current ?? []))>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
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
@endif

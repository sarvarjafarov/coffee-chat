@php
    $interview = $interview ?? null;
    $typeOptions = $typeOptions ?? [];
    $statusOptions = $statusOptions ?? [];
    $reminderOptions = ['email' => 'Email', 'push' => 'Push'];
    $selectedReminders = old('reminder_channels', $interview->reminder_channels ?? array_keys($reminderOptions));
    $selectedReminders = is_array($selectedReminders) ? $selectedReminders : [];
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Interview type</label>
        <select name="interview_type" class="form-select" required>
            @foreach($typeOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('interview_type', $interview->interview_type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Difficulty</label>
        <input type="text" name="difficulty" class="form-control" value="{{ old('difficulty', $interview->difficulty) }}" placeholder="e.g. Medium">
    </div>
    <div class="col-md-4">
        <label class="form-label">Focus area</label>
        <input type="text" name="focus_area" class="form-control" value="{{ old('focus_area', $interview->focus_area) }}" placeholder="Product sense, SQL, leadership">
    </div>
    <div class="col-md-4">
        <label class="form-label">Scheduled at</label>
        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional($interview->scheduled_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Time zone</label>
        <input type="text" name="time_zone" class="form-control" value="{{ old('time_zone', $interview->time_zone) }}" placeholder="e.g. EST">
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $interview->duration_minutes) }}" min="15" max="240">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $interview->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Partner name</label>
        <input type="text" name="partner_name" class="form-control" value="{{ old('partner_name', $interview->partner_name) }}" placeholder="Who will interview you">
    </div>
    <div class="col-md-4">
        <label class="form-label">Partner email</label>
        <input type="email" name="partner_email" class="form-control" value="{{ old('partner_email', $interview->partner_email) }}" placeholder="Optional">
    </div>
    <div class="col-md-12">
        <label class="form-label">Join link (external)</label>
        <input type="url" name="join_url" class="form-control" value="{{ old('join_url', $interview->join_url) }}" placeholder="Zoom/Meet URL">
        <small class="text-subtle">We surface this in confirmations and ICS exports.</small>
    </div>
    <div class="col-md-6">
        <label class="form-label">Agenda / prep notes</label>
        <textarea name="agenda" rows="4" class="form-control" placeholder="Structure the session, questions, timing">{{ old('agenda', $interview->agenda) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Prep materials</label>
        <textarea name="prep_materials" rows="4" class="form-control" placeholder="Links to job desc, resume, decks">{{ old('prep_materials', $interview->prep_materials) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="4" class="form-control" placeholder="Internal notes or reminders">{{ old('notes', $interview->notes) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Feedback</label>
        <textarea name="feedback" rows="4" class="form-control" placeholder="Post-call feedback, action items">{{ old('feedback', $interview->feedback) }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label d-block">Reminders</label>
        @foreach($reminderOptions as $value => $label)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="reminder_{{ $value }}" name="reminder_channels[]" value="{{ $value }}" @checked(in_array($value, $selectedReminders))>
                <label class="form-check-label" for="reminder_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <small class="text-subtle d-block mt-1">We default to both email and push.</small>
    </div>
    <div class="col-md-4">
        <label class="form-label">Rating (1-5)</label>
        <input type="number" name="rating" class="form-control" min="1" max="5" value="{{ old('rating', $interview->rating) }}" placeholder="Optional">
    </div>
</div>

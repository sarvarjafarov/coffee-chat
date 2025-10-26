@php
    /** @var \App\Models\CoffeeChat $coffeeChat */
    $selectedChannels = old('channels', $coffeeChat->channels->pluck('id')->all());
@endphp

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="user_id">Owner</label>
        <select name="user_id" id="user_id" class="form-control" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('user_id', $coffeeChat->user_id ?? auth()->id()) == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="company_id">Company</label>
        <select name="company_id" id="company_id" class="form-control">
            <option value="">— Select company —</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" @selected(old('company_id', $coffeeChat->company_id) == $company->id)>{{ $company->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="contact_id">Contact</label>
        <select name="contact_id" id="contact_id" class="form-control">
            <option value="">— Select contact —</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" @selected(old('contact_id', $coffeeChat->contact_id) == $contact->id)>
                    {{ $contact->name }} @if($contact->company) ({{ $contact->company->name }}) @endif
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="position_title">Role / Position Discussed</label>
        <input type="text" class="form-control" id="position_title" name="position_title" value="{{ old('position_title', $coffeeChat->position_title) }}" placeholder="Product Manager, SWE Intern, etc.">
    </div>
    <div class="form-group col-md-3">
        <label for="scheduled_at">Scheduled Date</label>
        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at"
               value="{{ old('scheduled_at', optional($coffeeChat->scheduled_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="form-group col-md-3">
        <label for="time_zone">Time Zone</label>
        <input type="text" class="form-control" id="time_zone" name="time_zone" value="{{ old('time_zone', $coffeeChat->time_zone) }}" placeholder="UTC, EST, PST...">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="location">Location / Meeting Link</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $coffeeChat->location) }}">
    </div>
    <div class="form-group col-md-3">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $coffeeChat->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-2">
        <label for="duration_minutes">Duration (min)</label>
        <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" min="5" max="480"
               value="{{ old('duration_minutes', $coffeeChat->duration_minutes) }}">
    </div>
    <div class="form-group col-md-2">
        <label for="rating">Rating (1-5)</label>
        <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $coffeeChat->rating) }}">
    </div>
    <div class="form-group col-md-2">
        <label class="d-block">&nbsp;</label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_virtual" name="is_virtual" value="1" @checked(old('is_virtual', $coffeeChat->is_virtual))>
            <label class="form-check-label" for="is_virtual">Virtual meeting</label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="channels">Reach-out Channels</label>
    <select name="channels[]" id="channels" class="form-control" multiple>
        @foreach($channels as $channel)
            <option value="{{ $channel->id }}" @selected(in_array($channel->id, $selectedChannels, true))>
                {{ $channel->label }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Hold Cmd/Ctrl to select multiple channels.</small>
</div>

<div class="form-group">
    <label for="summary">Summary</label>
    <textarea name="summary" id="summary" rows="3" class="form-control">{{ old('summary', $coffeeChat->summary) }}</textarea>
</div>

<div class="form-group">
    <label for="key_takeaways">Key Takeaways</label>
    <textarea name="key_takeaways" id="key_takeaways" rows="3" class="form-control">{{ old('key_takeaways', $coffeeChat->key_takeaways) }}</textarea>
</div>

<div class="form-group">
    <label for="next_steps">Next Steps</label>
    <textarea name="next_steps" id="next_steps" rows="3" class="form-control">{{ old('next_steps', $coffeeChat->next_steps) }}</textarea>
</div>

<div class="form-group">
    <label for="notes">Internal Notes</label>
    <textarea name="notes" id="notes" rows="4" class="form-control">{{ old('notes', $coffeeChat->notes) }}</textarea>
</div>

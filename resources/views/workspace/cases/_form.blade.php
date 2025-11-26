@php
    $session = $session ?? null;
    $scoreFields = $scoreFields ?? [];
    $statusOptions = $statusOptions ?? [];
    $caseStudies = $caseStudies ?? collect();
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Case from library</label>
        <select name="case_study_id" class="form-select">
            <option value="">— Select a case —</option>
            @foreach($caseStudies as $caseStudy)
                <option value="{{ $caseStudy->id }}" @selected(old('case_study_id', $session->case_study_id) == $caseStudy->id)>
                    {{ $caseStudy->title }} ({{ ucfirst($caseStudy->difficulty) }})
                </option>
            @endforeach
        </select>
        <small class="text-subtle">Seeded library with market sizing, profitability, and ops cases.</small>
    </div>
    <div class="col-md-6">
        <label class="form-label">Custom title</label>
        <input type="text" name="custom_title" class="form-control" value="{{ old('custom_title', $session->custom_title) }}" placeholder="Optional if you want to name your own case">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $session->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Scheduled at</label>
        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', optional($session->scheduled_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Time zone</label>
        <input type="text" name="time_zone" class="form-control" value="{{ old('time_zone', $session->time_zone) }}" placeholder="e.g. PST">
    </div>
    <div class="col-md-4">
        <label class="form-label">Started at</label>
        <input type="datetime-local" name="started_at" class="form-control" value="{{ old('started_at', optional($session->started_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Completed at</label>
        <input type="datetime-local" name="completed_at" class="form-control" value="{{ old('completed_at', optional($session->completed_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" class="form-control" min="5" max="360" value="{{ old('duration_minutes', $session->duration_minutes) }}" placeholder="e.g. 40">
    </div>

    <div class="col-md-6">
        <label class="form-label">Reflection</label>
        <textarea name="reflection" rows="4" class="form-control" placeholder="What went well, what to fix">{{ old('reflection', $session->reflection) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="4" class="form-control" placeholder="Scratchpad, links, or frameworks">{{ old('notes', $session->notes) }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label d-flex align-items-center gap-2">
            <input type="checkbox" class="form-check-input" name="llm_feedback_opt_in" value="1" @checked(old('llm_feedback_opt_in', $session->llm_feedback_opt_in))>
            <span>LLM feedback opt-in</span>
        </label>
        <small class="text-subtle d-block mb-2">Adds an opt-in flag so we can safely request automated feedback later.</small>
        <textarea name="llm_feedback" rows="3" class="form-control" placeholder="Optional: paste feedback or AI summary">{{ old('llm_feedback', $session->llm_feedback) }}</textarea>
    </div>
</div>

<hr class="my-4 border-light border-opacity-25">
<h4 class="h5 text-white mb-3">Self-assessment (1-5)</h4>

<div class="row g-3">
    @foreach($scoreFields as $key => $label)
        <div class="col-md-3">
            <label class="form-label">{{ $label }}</label>
            <input type="number" name="scores[{{ $key }}]" class="form-control" min="1" max="5" value="{{ old('scores.'.$key, data_get($session->self_scores, $key)) }}" placeholder="1-5">
        </div>
    @endforeach
</div>

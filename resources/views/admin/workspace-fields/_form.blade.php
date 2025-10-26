@php
    $field = $field ?? null;
    $types = $types ?? [];
    $optionsString = old('options');
    if ($optionsString === null && in_array($field->type, ['select', 'multiselect'])) {
        $optionsString = collect($field->options)->map(function ($option) {
            if (is_array($option)) {
                return ($option['value'] ?? '') . '::' . ($option['label'] ?? '');
            }

            return (string) $option;
        })->implode("\n");
    }
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Form</label>
            <input type="text" name="form" class="form-control" value="{{ old('form', $field->form) }}" required>
            <small class="text-muted">Use <code>coffee_chat</code> for the log form.</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Key</label>
            <input type="text" name="key" class="form-control" value="{{ old('key', $field->key) }}" required>
            <small class="text-muted">Lowercase + underscores, e.g. <code>agenda</code>.</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Label</label>
            <input type="text" name="label" class="form-control" value="{{ old('label', $field->label) }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control" required>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $field->type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group form-check mt-4">
            <input type="checkbox" class="form-check-input" id="required" name="required" value="1" @checked(old('required', $field->required))>
            <label class="form-check-label" for="required">Required</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group form-check mt-4">
            <input type="checkbox" class="form-check-input" id="active" name="active" value="1" @checked(old('active', $field->active ?? true))>
            <label class="form-check-label" for="active">Active</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group form-check mt-4">
            <input type="checkbox" class="form-check-input" id="in_analytics" name="in_analytics" value="1" @checked(old('in_analytics', $field->in_analytics))>
            <label class="form-check-label" for="in_analytics">Show in analytics</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Position</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', $field->position) }}" min="0">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Placeholder</label>
            <input type="text" name="placeholder" class="form-control" value="{{ old('placeholder', $field->placeholder) }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Help text</label>
            <input type="text" name="help_text" class="form-control" value="{{ old('help_text', $field->help_text) }}">
        </div>
    </div>
</div>

<div class="form-group">
    <label>Options (for select / multi-select)</label>
    <textarea name="options" class="form-control" rows="4" placeholder="value::Label">{{ $optionsString }}</textarea>
    <small class="text-muted">One option per line. Use <code>value::Label</code>. Leave blank for other field types.</small>
</div>

<div class="form-group">
    <label>Validation rules (JSON)</label>
    <textarea name="validation" class="form-control" rows="3" placeholder='{"min":3,"max":255}'>{{ old('validation', $field->validation ? json_encode($field->validation, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
</div>

<div class="form-group">
    <label>Style overrides (JSON)</label>
    <textarea name="style" class="form-control" rows="3" placeholder='{"column_span": "6"}'>{{ old('style', $field->style ? json_encode($field->style, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
</div>

<div class="form-group">
    <label>Meta (JSON)</label>
    <textarea name="meta" class="form-control" rows="3" placeholder='{"analytics_label": "Priority"}'>{{ old('meta', $field->meta ? json_encode($field->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
</div>

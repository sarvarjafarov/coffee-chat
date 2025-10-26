<div class="form-row">
    <div class="form-group col-md-4">
        <label for="key">Key</label>
        <input type="text" id="key" name="key" class="form-control" value="{{ old('key', $component->key ?? '') }}" required>
        <small class="text-muted">Example: <code>hero</code>, <code>features</code>, <code>cta</code>.</small>
    </div>
    <div class="form-group col-md-4">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $component->title ?? '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="position">Position</label>
        <input type="number" id="position" name="position" class="form-control" value="{{ old('position', $component->position ?? 0) }}" min="0">
    </div>
</div>

<div class="form-group">
    <label for="subtitle">Subtitle</label>
    <textarea id="subtitle" name="subtitle" rows="2" class="form-control">{{ old('subtitle', $component->subtitle ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="content">Content</label>
    <textarea id="content" name="content" rows="3" class="form-control">{{ old('content', $component->content ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="media">Media</label>
    <textarea id="media" name="media" rows="2" class="form-control">{{ old('media', $component->media ?? '') }}</textarea>
    <small class="text-muted">Optional field for storing media URLs or identifiers.</small>
</div>

<div class="form-group">
    <label for="meta">Meta JSON</label>
    <textarea id="meta" name="meta" rows="10" class="form-control" placeholder='{"stats": [...]}'>{{ old('meta', $metaJson ?? ($component->meta ? json_encode($component->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '')) }}</textarea>
    <small class="text-muted">Provide additional structured data as JSON. Example: <code>{"stats": [{"value": "+2k", "label": "Coffee chats"}]}</code>.</small>
</div>

<div class="form-group">
    <label for="style">Style overrides (JSON)</label>
    <textarea id="style" name="style" rows="6" class="form-control" placeholder='{"hero": {"heading_color": "#E0E7FF"}}'>{{ old('style', $styleJson ?? ($component->style ? json_encode($component->style, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '')) }}</textarea>
    <small class="text-muted">Override typography, spacing, and colour tokens per block.</small>
</div>

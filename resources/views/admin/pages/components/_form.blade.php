@php
    $componentKey = old('key', $component->key ?? '');
    $pageSlug = $page->slug ?? null;
    $blueprintKey = $componentKey && $pageSlug ? "{$pageSlug}.{$componentKey}" : $componentKey;

    $metaState = old('meta');
    if (! is_array($metaState)) {
        $metaState = $component->meta ?? [];
    }

    $styleState = old('style');
    if (! is_array($styleState)) {
        $styleState = $component->style ?? [];
    }

    $componentForms = [
        'home.hero' => 'admin.pages.components.forms.home-hero',
        'home.features' => 'admin.pages.components.forms.home-features',
        'home.ritual' => 'admin.pages.components.forms.home-ritual',
        'home.cta' => 'admin.pages.components.forms.home-cta',
        'insights.hero' => 'admin.pages.components.forms.insights-hero',
        'insights.highlights' => 'admin.pages.components.forms.insights-highlights',
        'insights.metrics' => 'admin.pages.components.forms.insights-metrics',
        'stories.hero' => 'admin.pages.components.forms.stories-hero',
    ];

    $formPartial = $componentForms[$blueprintKey] ?? null;

    $metaJsonValue = old('meta_json', $formPartial ? '' : ($metaJson ?? ($component->meta ? json_encode($component->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')));
    $styleJsonValue = old('style_json', $formPartial ? '' : ($styleJson ?? ($component->style ? json_encode($component->style, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')));
@endphp

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="key">Key</label>
        <input type="text" id="key" name="key" class="form-control" value="{{ $componentKey }}" required>
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

@if($formPartial)
    @include($formPartial, [
        'meta' => $metaState,
        'style' => $styleState,
        'componentKey' => $componentKey,
        'pageSlug' => $pageSlug,
    ])
@endif

<div class="card card-outline card-secondary mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Advanced JSON</span>
        <span class="badge badge-light text-uppercase">Optional</span>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            These fields override any structured inputs above. Leave them blank to rely on the friendly form.
        </p>
        <div class="form-group">
            <label for="meta_json">Meta JSON</label>
            <textarea id="meta_json" name="meta_json" rows="8" class="form-control" placeholder='{"stats": [{"value": "+2k", "label": "Coffee chats"}]}'>{{ $metaJsonValue }}</textarea>
        </div>
        <div class="form-group mb-0">
            <label for="style_json">Style overrides (JSON)</label>
            <textarea id="style_json" name="style_json" rows="6" class="form-control" placeholder='{"hero": {"heading_color": "#E0E7FF"}}'>{{ $styleJsonValue }}</textarea>
        </div>
    </div>
</div>

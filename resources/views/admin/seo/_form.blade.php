@php
    $schemaValue = old('schema');
    if ($schemaValue === null) {
        $schemaValue = $seo->schema ? json_encode($seo->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
    }
@endphp

<div class="form-group">
    <label for="title">SEO Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $seo->title) }}" placeholder="Page title">
    <small class="text-muted">Appears in the &lt;title&gt; tag and social previews.</small>
    @error('title')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Meta Description</label>
    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Concise summary for search results">{{ old('description', $seo->description) }}</textarea>
    @error('description')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="author">Author</label>
        <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $seo->author) }}" placeholder="Author name">
        @error('author')
            <span class="text-danger small d-block">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="image">Share Image URL</label>
        <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $seo->image) }}" placeholder="images/seo/share.png">
        <small class="text-muted">Public path relative to the app root or absolute URL.</small>
        @error('image')
            <span class="text-danger small d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="canonical_url">Canonical URL</label>
        <input type="text" class="form-control" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url ?? '') }}" placeholder="https://example.com/page">
        @error('canonical_url')
            <span class="text-danger small d-block">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="robots">Robots Directives</label>
        <input type="text" class="form-control" id="robots" name="robots" value="{{ old('robots', $seo->robots ?? '') }}" placeholder="noindex,nofollow">
        <small class="text-muted">Leave blank to use defaults from the SEO config.</small>
        @error('robots')
            <span class="text-danger small d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="schema">Structured data (JSON-LD)</label>
    <textarea class="form-control" id="schema" name="schema" rows="8" placeholder='{"@@context":"https://schema.org","@@type":"Article"}'>{{ $schemaValue }}</textarea>
    <small class="text-muted">Optional JSON-LD payload that will be output in a &lt;script type="application/ld+json"&gt; tag.</small>
    @error('schema')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

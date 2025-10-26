<div class="form-row">
    <div class="form-group col-md-4">
        <label for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $seoMeta->slug ?? '') }}" required>
        <small class="text-muted">Matches page slug or identifier (e.g. <code>home</code>, <code>stories</code>).</small>
    </div>
    <div class="form-group col-md-4">
        <label for="page_id">Linked Page</label>
        <select class="form-control" id="page_id" name="page_id">
            <option value="">— None —</option>
            @foreach($pages as $pageOption)
                <option value="{{ $pageOption->id }}" @selected(old('page_id', $seoMeta->page_id ?? '') == $pageOption->id)>{{ $pageOption->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="canonical_url">Canonical URL</label>
        <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $seoMeta->canonical_url ?? '') }}" placeholder="https://example.com/page">
    </div>
</div>

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $seoMeta->title ?? '') }}">
</div>

<div class="form-group">
    <label for="description">Meta Description</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $seoMeta->description ?? '') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="keywords">Keywords</label>
        <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords', $seoMeta->keywords ?? '') }}" placeholder="Comma separated">
    </div>
    <div class="form-group col-md-6">
        <label for="twitter_card">Twitter Card</label>
        <input type="text" class="form-control" id="twitter_card" name="twitter_card" value="{{ old('twitter_card', $seoMeta->twitter_card ?? 'summary_large_image') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="og_title">Open Graph Title</label>
        <input type="text" class="form-control" id="og_title" name="og_title" value="{{ old('og_title', $seoMeta->og_title ?? '') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="og_image">Open Graph Image URL</label>
        <input type="text" class="form-control" id="og_image" name="og_image" value="{{ old('og_image', $seoMeta->og_image ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label for="og_description">Open Graph Description</label>
    <textarea class="form-control" id="og_description" name="og_description" rows="3">{{ old('og_description', $seoMeta->og_description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="meta">Additional meta (JSON)</label>
    <textarea class="form-control" id="meta" name="meta" rows="6" placeholder='{"alternate": [{"hreflang": "en", "href": "https://..."}] }'>{{ old('meta', $metaJson ?? '') }}</textarea>
</div>

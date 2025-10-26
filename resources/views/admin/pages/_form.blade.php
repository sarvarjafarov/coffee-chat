<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $page->name ?? '') }}" required>
</div>

<div class="form-group">
    <label for="slug">Slug</label>
    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $page->slug ?? '') }}" required>
    <small class="text-muted">Used in URLs, e.g. <code>/{{ old('slug', $page->slug ?? 'home') }}</code>.</small>
</div>

<div class="form-group">
    <label for="description">Description</label>
    <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $page->description ?? '') }}">
</div>

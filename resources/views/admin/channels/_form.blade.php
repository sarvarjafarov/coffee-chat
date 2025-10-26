<div class="form-group">
    <label for="label">Label</label>
    <input type="text" id="label" name="label" class="form-control" value="{{ old('label', $channel->label ?? '') }}" required>
</div>

<div class="form-group">
    <label for="slug">Slug</label>
    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $channel->slug ?? '') }}" placeholder="Optional — generated from label if blank">
</div>

<div class="form-group">
    <label for="description">Description</label>
    <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $channel->description ?? '') }}">
</div>

@php
    /** @var \App\Models\Post|null $post */
@endphp

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" class="form-control"
           value="{{ old('title', $post->title ?? '') }}" required>
</div>

<div class="form-group">
    <label for="slug">Slug</label>
    <input type="text" id="slug" name="slug" class="form-control"
           value="{{ old('slug', $post->slug ?? '') }}"
           placeholder="Leave blank to auto-generate">
</div>

<div class="form-group">
    <label for="excerpt">Excerpt</label>
    <textarea id="excerpt" name="excerpt" rows="3" class="form-control"
              placeholder="Optional short summary (max 500 characters)">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="body">Body</label>
    <textarea id="body" name="body" rows="10" class="form-control" required>{{ old('body', $post->body ?? '') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="published_at">Publish Date</label>
        <input type="datetime-local" id="published_at" name="published_at" class="form-control"
               value="{{ old('published_at', optional($post->published_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="form-group col-md-4">
        <label class="d-block">Status</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="is_published"
                   name="is_published" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Published</label>
        </div>
    </div>
</div>

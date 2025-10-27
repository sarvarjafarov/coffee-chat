@php
    $steps = data_get($meta, 'steps', []);
    $stepsCount = max(is_array($steps) ? count($steps) + 1 : 1, 4);
    $trusted = data_get($meta, 'trusted', []);
    $testimonial = data_get($meta, 'testimonial', []);
    $networkHealth = data_get($meta, 'network_health', []);
    $ritualStyle = data_get($style, 'ritual', []);
@endphp

<div class="card card-outline card-success mt-4">
    <div class="card-header">
        Ritual Steps
    </div>
    <div class="card-body">
        @for($i = 0; $i < $stepsCount; $i++)
            <div class="border rounded p-3 mb-3">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Label #{{ $i + 1 }}</label>
                        <input type="text" name="meta[steps][{{ $i }}][label]" class="form-control" value="{{ old("meta.steps.$i.label", data_get($meta, "steps.$i.label")) }}" placeholder="01 · Canvas">
                    </div>
                    <div class="form-group col-md-9">
                        <label>Description</label>
                        <input type="text" name="meta[steps][{{ $i }}][description]" class="form-control" value="{{ old("meta.steps.$i.description", data_get($meta, "steps.$i.description")) }}">
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="card card-outline card-success mt-4">
    <div class="card-header">
        Trusted By
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Eyebrow</label>
                <input type="text" name="meta[trusted][title]" class="form-control" value="{{ old('meta.trusted.title', data_get($trusted, 'title')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Headline</label>
                <input type="text" name="meta[trusted][headline]" class="form-control" value="{{ old('meta.trusted.headline', data_get($trusted, 'headline')) }}">
            </div>
            <div class="form-group col-md-5">
                <label>Description</label>
                <input type="text" name="meta[trusted][body]" class="form-control" value="{{ old('meta.trusted.body', data_get($trusted, 'body')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-success mt-4">
    <div class="card-header">
        Testimonial
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Quote</label>
            <textarea name="meta[testimonial][quote]" rows="2" class="form-control">{{ old('meta.testimonial.quote', data_get($testimonial, 'quote')) }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Author</label>
                <input type="text" name="meta[testimonial][author]" class="form-control" value="{{ old('meta.testimonial.author', data_get($testimonial, 'author')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Role</label>
                <input type="text" name="meta[testimonial][role]" class="form-control" value="{{ old('meta.testimonial.role', data_get($testimonial, 'role')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Badge</label>
                <input type="text" name="meta[testimonial][badge]" class="form-control" value="{{ old('meta.testimonial.badge', data_get($testimonial, 'badge')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-success mt-4">
    <div class="card-header">
        Network Health
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Title</label>
                <input type="text" name="meta[network_health][title]" class="form-control" value="{{ old('meta.network_health.title', data_get($networkHealth, 'title')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Value</label>
                <input type="text" name="meta[network_health][value]" class="form-control" value="{{ old('meta.network_health.value', data_get($networkHealth, 'value')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Progress (%)</label>
                <input type="number" min="0" max="100" name="meta[network_health][progress]" class="form-control" value="{{ old('meta.network_health.progress', data_get($networkHealth, 'progress')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Tag</label>
                <input type="text" name="meta[network_health][tag]" class="form-control" value="{{ old('meta.network_health.tag', data_get($networkHealth, 'tag')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Description</label>
                <input type="text" name="meta[network_health][description]" class="form-control" value="{{ old('meta.network_health.description', data_get($networkHealth, 'description')) }}">
            </div>
            <div class="form-group col-md-6">
                <label>Footnote</label>
                <input type="text" name="meta[network_health][footnote]" class="form-control" value="{{ old('meta.network_health.footnote', data_get($networkHealth, 'footnote')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-success mt-4">
    <div class="card-header">
        Ritual Style
    </div>
    <div class="card-body">
        <div class="form-group mb-0">
            <label>Testimonial background</label>
            <input type="text" name="style[ritual][testimonial_background]" class="form-control" value="{{ old('style.ritual.testimonial_background', data_get($ritualStyle, 'testimonial_background')) }}" placeholder="linear-gradient(...)">
        </div>
    </div>
</div>

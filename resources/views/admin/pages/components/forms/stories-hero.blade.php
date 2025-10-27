@php
    $cta = data_get($meta, 'cta', []);
@endphp

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Stories Hero Meta
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>CTA label</label>
                <input type="text" name="meta[cta][label]" class="form-control" value="{{ old('meta.cta.label', data_get($cta, 'label')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>CTA URL</label>
                <input type="text" name="meta[cta][url]" class="form-control" value="{{ old('meta.cta.url', data_get($cta, 'url')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Hero stat</label>
                <input type="text" name="meta[stat]" class="form-control" value="{{ old('meta.stat', data_get($meta, 'stat')) }}" placeholder="Updated weekly with fresh narratives.">
            </div>
        </div>
    </div>
</div>

@php
    $ctaStyle = data_get($style, 'cta', []);
@endphp

<div class="card card-outline card-warning mt-4">
    <div class="card-header">
        CTA Buttons
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Primary label</label>
                <input type="text" name="meta[primary_button][label]" class="form-control" value="{{ old('meta.primary_button.label', data_get($meta, 'primary_button.label')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Primary URL</label>
                <input type="text" name="meta[primary_button][url]" class="form-control" value="{{ old('meta.primary_button.url', data_get($meta, 'primary_button.url')) }}">
            </div>
            <div class="form-group col-md-2">
                <label>Primary icon</label>
                <input type="text" name="meta[primary_button][icon]" class="form-control" value="{{ old('meta.primary_button.icon', data_get($meta, 'primary_button.icon')) }}" placeholder="mdi-send">
            </div>
            <div class="form-group col-md-3">
                <label>Secondary label</label>
                <input type="text" name="meta[secondary_button][label]" class="form-control" value="{{ old('meta.secondary_button.label', data_get($meta, 'secondary_button.label')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Secondary URL</label>
                <input type="text" name="meta[secondary_button][url]" class="form-control" value="{{ old('meta.secondary_button.url', data_get($meta, 'secondary_button.url')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Secondary icon</label>
                <input type="text" name="meta[secondary_button][icon]" class="form-control" value="{{ old('meta.secondary_button.icon', data_get($meta, 'secondary_button.icon')) }}" placeholder="mdi-arrow-right">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-warning mt-4">
    <div class="card-header">
        CTA Style
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Background</label>
            <textarea name="style[cta][background]" rows="2" class="form-control">{{ old('style.cta.background', data_get($ctaStyle, 'background')) }}</textarea>
        </div>
        <div class="form-group">
            <label>Overlay</label>
            <textarea name="style[cta][overlay]" rows="2" class="form-control">{{ old('style.cta.overlay', data_get($ctaStyle, 'overlay')) }}</textarea>
        </div>
        <div class="form-group mb-0">
            <label>Padding</label>
            <input type="text" name="style[cta][padding]" class="form-control" value="{{ old('style.cta.padding', data_get($ctaStyle, 'padding')) }}" placeholder="clamp(3rem, 6vw, 4rem) clamp(2rem, 6vw, 4.5rem)">
        </div>
    </div>
</div>

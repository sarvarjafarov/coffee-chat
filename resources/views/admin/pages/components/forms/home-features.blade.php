@php
    $featuresList = data_get($meta, 'features', []);
    $featuresCount = max(is_array($featuresList) ? count($featuresList) + 1 : 1, 3);
    $featuresStyle = data_get($style, 'features', []);
@endphp

<div class="card card-outline card-info mt-4">
    <div class="card-header">
        Feature Section
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Eyebrow label</label>
                <input type="text" name="meta[eyebrow]" class="form-control" value="{{ old('meta.eyebrow', data_get($meta, 'eyebrow')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>CTA label</label>
                <input type="text" name="meta[cta_link][label]" class="form-control" value="{{ old('meta.cta_link.label', data_get($meta, 'cta_link.label')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>CTA URL</label>
                <input type="text" name="meta[cta_link][url]" class="form-control" value="{{ old('meta.cta_link.url', data_get($meta, 'cta_link.url')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-info mt-4">
    <div class="card-header">
        Feature Cards
    </div>
    <div class="card-body">
        @for($i = 0; $i < $featuresCount; $i++)
            <div class="border rounded p-3 mb-3">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Icon</label>
                        <input type="text" name="meta[features][{{ $i }}][icon]" class="form-control" value="{{ old("meta.features.$i.icon", data_get($meta, "features.$i.icon")) }}" placeholder="mdi-rocket">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Title</label>
                        <input type="text" name="meta[features][{{ $i }}][title]" class="form-control" value="{{ old("meta.features.$i.title", data_get($meta, "features.$i.title")) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Description</label>
                        <input type="text" name="meta[features][{{ $i }}][description]" class="form-control" value="{{ old("meta.features.$i.description", data_get($meta, "features.$i.description")) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Link text</label>
                        <input type="text" name="meta[features][{{ $i }}][link_text]" class="form-control" value="{{ old("meta.features.$i.link_text", data_get($meta, "features.$i.link_text")) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Link URL</label>
                        <input type="text" name="meta[features][{{ $i }}][link_url]" class="form-control" value="{{ old("meta.features.$i.link_url", data_get($meta, "features.$i.link_url")) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Footnote</label>
                        <input type="text" name="meta[features][{{ $i }}][footnote]" class="form-control" value="{{ old("meta.features.$i.footnote", data_get($meta, "features.$i.footnote")) }}">
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="card card-outline card-info mt-4">
    <div class="card-header">
        Feature Style
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Card background</label>
                <input type="text" name="style[features][card_background]" class="form-control" value="{{ old('style.features.card_background', data_get($featuresStyle, 'card_background')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Card border colour</label>
                <input type="text" name="style[features][card_border_color]" class="form-control" value="{{ old('style.features.card_border_color', data_get($featuresStyle, 'card_border_color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Title colour</label>
                <input type="text" name="style[features][title_color]" class="form-control" value="{{ old('style.features.title_color', data_get($featuresStyle, 'title_color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Description colour</label>
                <input type="text" name="style[features][description_color]" class="form-control" value="{{ old('style.features.description_color', data_get($featuresStyle, 'description_color')) }}">
            </div>
        </div>
    </div>
</div>

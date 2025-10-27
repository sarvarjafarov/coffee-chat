@php
    $cards = data_get($meta, 'cards', []);
    $cardCount = max(is_array($cards) ? count($cards) + 1 : 1, 3);
@endphp

<div class="card card-outline card-info mt-4">
    <div class="card-header">
        Highlight Cards
    </div>
    <div class="card-body">
        @for($i = 0; $i < $cardCount; $i++)
            <div class="border rounded p-3 mb-3">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Tag #{{ $i + 1 }}</label>
                        <input type="text" name="meta[cards][{{ $i }}][tag]" class="form-control" value="{{ old("meta.cards.$i.tag", data_get($meta, "cards.$i.tag")) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Title</label>
                        <input type="text" name="meta[cards][{{ $i }}][title]" class="form-control" value="{{ old("meta.cards.$i.title", data_get($meta, "cards.$i.title")) }}">
                    </div>
                    <div class="form-group col-md-5">
                        <label>Body</label>
                        <input type="text" name="meta[cards][{{ $i }}][body]" class="form-control" value="{{ old("meta.cards.$i.body", data_get($meta, "cards.$i.body")) }}">
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

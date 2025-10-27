@php
    $chips = data_get($meta, 'chips', []);
    $chipCount = max(is_array($chips) ? count($chips) + 1 : 1, 4);
@endphp

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Insight Hero Chips
    </div>
    <div class="card-body">
        @for($i = 0; $i < $chipCount; $i++)
            <div class="form-group">
                <label>Chip {{ $i + 1 }}</label>
                <input type="text" name="meta[chips][{{ $i }}]" class="form-control" value="{{ old("meta.chips.$i", data_get($meta, "chips.$i")) }}">
            </div>
        @endfor
    </div>
</div>

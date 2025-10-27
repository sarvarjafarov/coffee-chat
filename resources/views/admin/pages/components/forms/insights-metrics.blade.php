@php
    $metrics = data_get($meta, 'metrics', []);
    $metricCount = max(is_array($metrics) ? count($metrics) + 1 : 1, 4);
@endphp

<div class="card card-outline card-warning mt-4">
    <div class="card-header">
        Insight Metrics
    </div>
    <div class="card-body">
        @for($i = 0; $i < $metricCount; $i++)
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Label #{{ $i + 1 }}</label>
                    <input type="text" name="meta[metrics][{{ $i }}][label]" class="form-control" value="{{ old("meta.metrics.$i.label", data_get($meta, "metrics.$i.label")) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Value</label>
                    <input type="text" name="meta[metrics][{{ $i }}][value]" class="form-control" value="{{ old("meta.metrics.$i.value", data_get($meta, "metrics.$i.value")) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Change</label>
                    <input type="text" name="meta[metrics][{{ $i }}][change]" class="form-control" value="{{ old("meta.metrics.$i.change", data_get($meta, "metrics.$i.change")) }}" placeholder="+6% WoW">
                </div>
            </div>
        @endfor
    </div>
</div>

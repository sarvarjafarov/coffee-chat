@php
    $stats = data_get($meta, 'stats', []);
    $statsCount = max(is_array($stats) ? count($stats) + 1 : 1, 3);

    $timeline = data_get($meta, 'timeline', []);
    $timelineCount = max(is_array($timeline) ? count($timeline) + 1 : 1, 3);

    $channels = data_get($meta, 'channels', []);
    $channelsCount = max(is_array($channels) ? count($channels) + 1 : 1, 4);

    $pills = data_get($meta, 'pills', []);
    $pillsCount = max(is_array($pills) ? count($pills) + 1 : 1, 4);

    $heroStyle = data_get($style, 'hero', []);
@endphp

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Hero Details
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Hero badge</label>
                <input type="text" name="meta[badge]" class="form-control" value="{{ old('meta.badge', data_get($meta, 'badge')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Primary button label</label>
                <input type="text" name="meta[primary_button][label]" class="form-control" value="{{ old('meta.primary_button.label', data_get($meta, 'primary_button.label')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Primary button URL</label>
                <input type="text" name="meta[primary_button][url]" class="form-control" value="{{ old('meta.primary_button.url', data_get($meta, 'primary_button.url')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Primary button icon</label>
                <input type="text" name="meta[primary_button][icon]" class="form-control" value="{{ old('meta.primary_button.icon', data_get($meta, 'primary_button.icon')) }}" placeholder="mdi-rocket-launch-outline">
            </div>
            <div class="form-group col-md-4">
                <label>Secondary button label</label>
                <input type="text" name="meta[secondary_button][label]" class="form-control" value="{{ old('meta.secondary_button.label', data_get($meta, 'secondary_button.label')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Secondary button URL</label>
                <input type="text" name="meta[secondary_button][url]" class="form-control" value="{{ old('meta.secondary_button.url', data_get($meta, 'secondary_button.url')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Rating stars</label>
                <input type="number" min="1" max="5" name="meta[rating][stars]" class="form-control" value="{{ old('meta.rating.stars', data_get($meta, 'rating.stars', 5)) }}">
            </div>
            <div class="form-group col-md-5">
                <label>Rating text</label>
                <input type="text" name="meta[rating][text]" class="form-control" value="{{ old('meta.rating.text', data_get($meta, 'rating.text')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Rating caption</label>
                <input type="text" name="meta[rating][caption]" class="form-control" value="{{ old('meta.rating.caption', data_get($meta, 'rating.caption')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Stats
    </div>
    <div class="card-body">
        @for($i = 0; $i < $statsCount; $i++)
            <div class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label>Value #{{ $i + 1 }}</label>
                    <input type="text" name="meta[stats][{{ $i }}][value]" class="form-control" value="{{ old("meta.stats.$i.value", data_get($meta, "stats.$i.value")) }}">
                </div>
                <div class="form-group col-md-7">
                    <label>Label #{{ $i + 1 }}</label>
                    <input type="text" name="meta[stats][{{ $i }}][label]" class="form-control" value="{{ old("meta.stats.$i.label", data_get($meta, "stats.$i.label")) }}">
                </div>
                <div class="form-group col-md-2">
                    <label class="d-flex align-items-center justify-content-between">
                        Status
                        <span class="badge badge-light">Optional</span>
                    </label>
                    <input type="text" name="meta[stats][{{ $i }}][status]" class="form-control" value="{{ old("meta.stats.$i.status", data_get($meta, "stats.$i.status")) }}" placeholder="Up 4%">
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Conversation Flow
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Timeline badge</label>
                <input type="text" name="meta[timeline_badge]" class="form-control" value="{{ old('meta.timeline_badge', data_get($meta, 'timeline_badge')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Timeline title</label>
                <input type="text" name="meta[timeline_title]" class="form-control" value="{{ old('meta.timeline_title', data_get($meta, 'timeline_title')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Timeline description</label>
                <input type="text" name="meta[timeline_description]" class="form-control" value="{{ old('meta.timeline_description', data_get($meta, 'timeline_description')) }}">
            </div>
        </div>
        @for($i = 0; $i < $timelineCount; $i++)
            <div class="border rounded p-3 mb-3">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Step title #{{ $i + 1 }}</label>
                        <input type="text" name="meta[timeline][{{ $i }}][title]" class="form-control" value="{{ old("meta.timeline.$i.title", data_get($meta, "timeline.$i.title", data_get($meta, "timeline.$i.label"))) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Step description</label>
                        <input type="text" name="meta[timeline][{{ $i }}][description]" class="form-control" value="{{ old("meta.timeline.$i.description", data_get($meta, "timeline.$i.description")) }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Status</label>
                        <input type="text" name="meta[timeline][{{ $i }}][status]" class="form-control" value="{{ old("meta.timeline.$i.status", data_get($meta, "timeline.$i.status")) }}" placeholder="Due in 6 hrs">
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Upcoming Chat & Confidence Pulse
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Next chat label</label>
                <input type="text" name="meta[next_chat][label]" class="form-control" value="{{ old('meta.next_chat.label', data_get($meta, 'next_chat.label')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Next chat title</label>
                <input type="text" name="meta[next_chat][title]" class="form-control" value="{{ old('meta.next_chat.title', data_get($meta, 'next_chat.title')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Next chat when</label>
                <input type="text" name="meta[next_chat][schedule]" class="form-control" value="{{ old('meta.next_chat.schedule', data_get($meta, 'next_chat.schedule')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Next chat notes</label>
                <input type="text" name="meta[next_chat][notes]" class="form-control" value="{{ old('meta.next_chat.notes', data_get($meta, 'next_chat.notes')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Workspace URL</label>
                <input type="text" name="meta[next_chat][link]" class="form-control" value="{{ old('meta.next_chat.link', data_get($meta, 'next_chat.link')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Workspace button label</label>
                <input type="text" name="meta[next_chat][cta]" class="form-control" value="{{ old('meta.next_chat.cta', data_get($meta, 'next_chat.cta')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Confidence block title</label>
                <input type="text" name="meta[confidence][title]" class="form-control" value="{{ old('meta.confidence.title', data_get($meta, 'confidence.title')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Confidence score</label>
                <input type="text" name="meta[confidence][score]" class="form-control" value="{{ old('meta.confidence.score', data_get($meta, 'confidence.score')) }}">
            </div>
            <div class="form-group col-md-5">
                <label>Confidence status</label>
                <input type="text" name="meta[confidence][status]" class="form-control" value="{{ old('meta.confidence.status', data_get($meta, 'confidence.status')) }}">
            </div>
            <div class="form-group col-md-4">
                <label>Confidence caption</label>
                <input type="text" name="meta[confidence][caption]" class="form-control" value="{{ old('meta.confidence.caption', data_get($meta, 'confidence.caption')) }}">
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Channels & Product Pills
    </div>
    <div class="card-body">
        <h6 class="text-muted">Channels</h6>
        @for($i = 0; $i < $channelsCount; $i++)
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label>Channel label #{{ $i + 1 }}</label>
                    <input type="text" name="meta[channels][{{ $i }}][label]" class="form-control" value="{{ old("meta.channels.$i.label", data_get($meta, "channels.$i.label")) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Channel icon</label>
                    <input type="text" name="meta[channels][{{ $i }}][icon]" class="form-control" value="{{ old("meta.channels.$i.icon", data_get($meta, "channels.$i.icon")) }}" placeholder="mdi-email-outline">
                </div>
            </div>
        @endfor

        <h6 class="text-muted mt-4">Product pills</h6>
        @for($i = 0; $i < $pillsCount; $i++)
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Pill label #{{ $i + 1 }}</label>
                    <input type="text" name="meta[pills][{{ $i }}][label]" class="form-control" value="{{ old("meta.pills.$i.label", data_get($meta, "pills.$i.label")) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Pill icon</label>
                    <input type="text" name="meta[pills][{{ $i }}][icon]" class="form-control" value="{{ old("meta.pills.$i.icon", data_get($meta, "pills.$i.icon")) }}" placeholder="mdi-flash">
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="card card-outline card-primary mt-4">
    <div class="card-header">
        Hero Style
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Background</label>
            <textarea name="style[hero][background]" rows="2" class="form-control" placeholder="CSS gradient">{{ old('style.hero.background', data_get($heroStyle, 'background')) }}</textarea>
        </div>
        <div class="form-group">
            <label>Overlay</label>
            <textarea name="style[hero][overlay]" rows="2" class="form-control">{{ old('style.hero.overlay', data_get($heroStyle, 'overlay')) }}</textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Heading colour</label>
                <input type="text" name="style[hero][heading_color]" class="form-control" value="{{ old('style.hero.heading_color', data_get($heroStyle, 'heading_color')) }}" placeholder="#0f172a">
            </div>
            <div class="form-group col-md-3">
                <label>Subtitle colour</label>
                <input type="text" name="style[hero][subtitle_color]" class="form-control" value="{{ old('style.hero.subtitle_color', data_get($heroStyle, 'subtitle_color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Primary button background</label>
                <input type="text" name="style[hero][primary_button][background]" class="form-control" value="{{ old('style.hero.primary_button.background', data_get($heroStyle, 'primary_button.background')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Primary button colour</label>
                <input type="text" name="style[hero][primary_button][color]" class="form-control" value="{{ old('style.hero.primary_button.color', data_get($heroStyle, 'primary_button.color')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Secondary button colour</label>
                <input type="text" name="style[hero][secondary_button][color]" class="form-control" value="{{ old('style.hero.secondary_button.color', data_get($heroStyle, 'secondary_button.color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Secondary button border</label>
                <input type="text" name="style[hero][secondary_button][border]" class="form-control" value="{{ old('style.hero.secondary_button.border', data_get($heroStyle, 'secondary_button.border')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Stats background</label>
                <input type="text" name="style[hero][stats][background]" class="form-control" value="{{ old('style.hero.stats.background', data_get($heroStyle, 'stats.background')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Stats border</label>
                <input type="text" name="style[hero][stats][border]" class="form-control" value="{{ old('style.hero.stats.border', data_get($heroStyle, 'stats.border')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Stats value colour</label>
                <input type="text" name="style[hero][stats][value_color]" class="form-control" value="{{ old('style.hero.stats.value_color', data_get($heroStyle, 'stats.value_color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Stats label colour</label>
                <input type="text" name="style[hero][stats][label_color]" class="form-control" value="{{ old('style.hero.stats.label_color', data_get($heroStyle, 'stats.label_color')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Channels badge background</label>
                <input type="text" name="style[hero][channels][badge_background]" class="form-control" value="{{ old('style.hero.channels.badge_background', data_get($heroStyle, 'channels.badge_background')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Channels badge colour</label>
                <input type="text" name="style[hero][channels][badge_color]" class="form-control" value="{{ old('style.hero.channels.badge_color', data_get($heroStyle, 'channels.badge_color')) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Channels badge border</label>
                <input type="text" name="style[hero][channels][badge_border]" class="form-control" value="{{ old('style.hero.channels.badge_border', data_get($heroStyle, 'channels.badge_border')) }}">
            </div>
            <div class="form-group col-md-3">
                <label>Channels title colour</label>
                <input type="text" name="style[hero][channels][title_color]" class="form-control" value="{{ old('style.hero.channels.title_color', data_get($heroStyle, 'channels.title_color')) }}">
            </div>
        </div>
    </div>
</div>

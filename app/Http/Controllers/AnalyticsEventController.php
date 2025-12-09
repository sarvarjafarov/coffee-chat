<?php

namespace App\Http\Controllers;

use App\Models\MarketingEvent;
use App\Models\MarketingTouchpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AnalyticsEventController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_name' => ['required', 'string', 'max:150'],
            'session_id' => ['required', 'string', 'max:120'],
            'properties' => ['nullable', 'array'],
            'path' => ['nullable', 'string', 'max:255'],
            'referrer' => ['nullable', 'string'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $sessionId = $data['session_id'];
        $userId = optional($request->user())->id;
        $properties = $data['properties'] ?? [];
        $eventName = $data['event_name'];
        $occurredAt = Carbon::parse($data['occurred_at'] ?? now());

        // Capture touchpoints when UTM/referrer/landing is present or explicitly flagged as touchpoint.
        $shouldLogTouchpoint = $eventName === 'touchpoint';
        $touchData = [
            'source' => $properties['utm_source'] ?? null,
            'medium' => $properties['utm_medium'] ?? null,
            'campaign' => $properties['utm_campaign'] ?? null,
            'content' => $properties['utm_content'] ?? null,
            'term' => $properties['utm_term'] ?? null,
            'referrer' => $properties['referrer'] ?? ($data['referrer'] ?? null),
            'landing_page' => $properties['landing_page'] ?? ($data['path'] ?? null),
        ];

        if (collect($touchData)->filter()->isNotEmpty()) {
            $shouldLogTouchpoint = true;
        }

        if ($shouldLogTouchpoint) {
            MarketingTouchpoint::create(array_merge($touchData, [
                'session_id' => $sessionId,
                'user_id' => $userId,
            ]));
        }

        MarketingEvent::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'event_name' => $eventName,
            'properties' => $properties,
            'path' => $data['path'] ?? null,
            'referrer' => $data['referrer'] ?? null,
            'occurred_at' => $occurredAt,
        ]);

        return response()->noContent();
    }
}

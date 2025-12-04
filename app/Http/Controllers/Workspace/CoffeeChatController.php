<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\CoffeeChat;
use App\Models\Company;
use App\Models\Contact;
use App\Models\WorkspaceField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CoffeeChatController extends Controller
{
    public function index(): View
    {
        $dynamicFields = WorkspaceField::formFields();

        $chats = CoffeeChat::with(['company', 'contact', 'channels'])
            ->where('user_id', auth()->id())
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        $allChats = CoffeeChat::with('channels')
            ->where('user_id', auth()->id())
            ->get();

        $statusCounts = $allChats->groupBy('status')->map->count();

        $channelCounts = [];
        foreach ($allChats as $chatItem) {
            foreach ($chatItem->channels as $channel) {
                $channelCounts[$channel->label] = ($channelCounts[$channel->label] ?? 0) + 1;
            }
        }

        return view('workspace.coffee-chats.index', [
            'chats' => $chats,
            'statusOptions' => $this->statusOptions(),
            'dynamicFields' => $dynamicFields,
            'totalChats' => $allChats->count(),
            'completedChats' => $allChats->where('status', 'completed')->count(),
            'activeChannels' => count($channelCounts),
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->isFree() && CoffeeChat::where('user_id', $user->id)->count() >= 10) {
            return redirect()->route('pricing')->withErrors('Upgrade to the Premium plan to create unlimited coffee chats.');
        }

        return view('workspace.coffee-chats.create', [
            'chat' => new CoffeeChat([
                'status' => 'planned',
                'is_virtual' => true,
            ]),
            'channels' => Channel::orderBy('label')->get(),
            'statusOptions' => $this->statusOptions(),
            'dynamicFields' => WorkspaceField::formFields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->isFree() && CoffeeChat::where('user_id', $user->id)->count() >= 10) {
            return redirect()->route('pricing')->withErrors('Upgrade to the Premium plan to create more than 10 coffee chats.');
        }

        $fields = WorkspaceField::formFields();
        $data = $this->validated($request, null, $fields);

        $company = $this->resolveCompany($data);
        $contact = $this->resolveContact($data, $company);

        $chat = CoffeeChat::create([
            'user_id' => $user->id,
            'company_id' => $company?->id,
            'contact_id' => $contact?->id,
            'position_title' => $data['position_title'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => $data['status'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'is_virtual' => $request->boolean('is_virtual'),
            'summary' => $data['summary'] ?? null,
            'key_takeaways' => $data['key_takeaways'] ?? null,
            'next_steps' => $data['next_steps'] ?? null,
            'notes' => $data['notes'] ?? null,
            'rating' => $data['rating'] ?? null,
            'extras' => $data['extras'] ?? null,
        ]);

        $chat->channels()->sync($data['channels'] ?? []);

        return redirect()->route('workspace.coffee-chats.index')
            ->with('status', 'Coffee chat logged successfully.');
    }

    public function edit(CoffeeChat $coffeeChat): View
    {
        $this->authorizeChat($coffeeChat);

        $coffeeChat->load('channels', 'company', 'contact');

        return view('workspace.coffee-chats.edit', [
            'chat' => $coffeeChat,
            'channels' => Channel::orderBy('label')->get(),
            'statusOptions' => $this->statusOptions(),
            'dynamicFields' => WorkspaceField::formFields(),
        ]);
    }

    public function update(Request $request, CoffeeChat $coffeeChat): RedirectResponse
    {
        $this->authorizeChat($coffeeChat);

        $fields = WorkspaceField::formFields();
        $data = $this->validated($request, $coffeeChat, $fields);
        $company = $this->resolveCompany($data);
        $contact = $this->resolveContact($data, $company);

        $incomingSchedule = $data['scheduled_at'] ? Carbon::parse($data['scheduled_at']) : null;
        $scheduleChanged = optional($coffeeChat->scheduled_at)->toDateTimeString() !== optional($incomingSchedule)->toDateTimeString();
        $statusBackToPlanned = $coffeeChat->status !== $data['status'] && $data['status'] === 'planned';

        $updatePayload = [
            'company_id' => $company?->id,
            'contact_id' => $contact?->id,
            'position_title' => $data['position_title'] ?? null,
            'scheduled_at' => $incomingSchedule,
            'time_zone' => $data['time_zone'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => $data['status'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'is_virtual' => $request->boolean('is_virtual'),
            'summary' => $data['summary'] ?? null,
            'key_takeaways' => $data['key_takeaways'] ?? null,
            'next_steps' => $data['next_steps'] ?? null,
            'notes' => $data['notes'] ?? null,
            'rating' => $data['rating'] ?? null,
            'extras' => $data['extras'] ?? null,
        ];

        if ($scheduleChanged || $statusBackToPlanned) {
            $updatePayload['reminder_sent_at'] = null;
        }

        $coffeeChat->update($updatePayload);

        $coffeeChat->channels()->sync($data['channels'] ?? []);

        return redirect()->route('workspace.coffee-chats.index')
            ->with('status', 'Coffee chat updated successfully.');
    }

    public function destroy(CoffeeChat $coffeeChat): RedirectResponse
    {
        $this->authorizeChat($coffeeChat);

        $coffeeChat->delete();

        return redirect()->route('workspace.coffee-chats.index')
            ->with('status', 'Coffee chat removed.');
    }

    public function calendar(): View
    {
        $chats = CoffeeChat::with(['company', 'contact'])
            ->where('user_id', auth()->id())
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')
            ->get();

        $statusOptions = $this->statusOptions();

        $events = $chats->map(function (CoffeeChat $chat) use ($statusOptions) {
            $scheduledAt = optional($chat->scheduled_at);
            $startUtc = $scheduledAt?->copy()->setTimezone('UTC');
            $endUtc = $startUtc?->copy()->addMinutes($chat->duration_minutes ?? 30);

            $googleUrl = null;
            if ($startUtc && $endUtc) {
                $googleUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                    .'&text='.urlencode(($chat->contact?->name ? $chat->contact->name.' · ' : '').($chat->company?->name ?? 'Coffee chat'))
                    .'&dates='.$startUtc->format('Ymd\THis\Z').'/'.$endUtc->format('Ymd\THis\Z')
                    .'&details='.urlencode($chat->summary ?? $chat->notes ?? '')
                    .($chat->location ? '&location='.urlencode($chat->location) : '');
            }

            return [
                'id' => $chat->id,
                'title' => $chat->company?->name
                    ? ($chat->contact?->name ? $chat->contact->name.' · '.$chat->company->name : $chat->company->name)
                    : ($chat->contact?->name ?? 'Coffee chat'),
                'start' => optional($chat->scheduled_at)->toIso8601String(),
                'location' => $chat->location,
                'status' => $statusOptions[$chat->status] ?? $chat->status,
                'notes' => $chat->summary ?? $chat->notes,
                'time_zone' => $chat->time_zone,
                'ics_url' => route('workspace.coffee-chats.ics', $chat),
                'google_url' => $googleUrl,
                'edit_url' => route('workspace.coffee-chats.edit', $chat),
            ];
        });

        return view('workspace.coffee-chats.calendar', [
            'events' => $events,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function ics(CoffeeChat $coffeeChat)
    {
        $this->authorizeChat($coffeeChat);

        abort_unless($coffeeChat->scheduled_at, 404);

        $start = $coffeeChat->scheduled_at->copy()->setTimezone('UTC');
        $end = $start->copy()->addMinutes($coffeeChat->duration_minutes ?? 30);

        $company = $coffeeChat->company?->name;
        $contact = $coffeeChat->contact?->name;
        $summary = trim(($company ? $company.' — ' : '').($contact ?? 'Coffee chat')) ?: 'Coffee chat';

        $ics = "BEGIN:VCALENDAR\r\n".
            "VERSION:2.0\r\n".
            "PRODID:-//CoffeeChat OS//EN\r\n".
            "BEGIN:VEVENT\r\n".
            "UID:coffeechat-{$coffeeChat->id}@coffeechat-os\r\n".
            "DTSTAMP:".$start->format('Ymd\THis\Z')."\r\n".
            "DTSTART:".$start->format('Ymd\THis\Z')."\r\n".
            "DTEND:".$end->format('Ymd\THis\Z')."\r\n".
            "SUMMARY:".addcslashes($summary, ",;\\")."\r\n".
            ($coffeeChat->location ? "LOCATION:".addcslashes($coffeeChat->location, ",;\\")."\r\n" : '').
            ($coffeeChat->notes ? "DESCRIPTION:".addcslashes($coffeeChat->notes, ",;\\") ."\r\n" : '').
            "END:VEVENT\r\nEND:VCALENDAR";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="coffee-chat-'.$coffeeChat->id.'.ics"',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?CoffeeChat $coffeeChat = null, $workspaceFields = null): array
    {
        $workspaceFields = $workspaceFields ?? collect();

        $rules = [
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_position' => ['nullable', 'string', 'max:255'],
            'time_zone' => ['nullable', 'string', 'max:64'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys($this->statusOptions()))],
            'scheduled_at' => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'between:5,480'],
            'position_title' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'key_takeaways' => ['nullable', 'string'],
            'next_steps' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'channels' => ['nullable', 'array'],
            'channels.*' => ['exists:channels,id'],
        ];

        $extras = [];

        foreach ($workspaceFields as $field) {
            $key = 'field_'.$field->key;
            $fieldRules = [];

            $fieldRules[] = $field->required ? 'required' : 'nullable';

            switch ($field->type) {
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'string';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'date':
                case 'datetime':
                    $fieldRules[] = 'date';
                    break;
                case 'boolean':
                    $fieldRules[] = 'boolean';
                    break;
                case 'select':
                    $values = $this->optionValues($field);
                    if ($values->isNotEmpty()) {
                        $fieldRules[] = Rule::in($values->all());
                    }
                    break;
                case 'multiselect':
                    $fieldRules[] = 'array';
                    $values = $this->optionValues($field);
                    if ($values->isNotEmpty()) {
                        $rules[$key.'.*'] = [Rule::in($values->all())];
                    }
                    break;
                default:
                    $fieldRules[] = 'string';
            }

            if ($field->validation) {
                foreach ($field->validation as $ruleKey => $ruleValue) {
                    if (is_numeric($ruleKey)) {
                        $fieldRules[] = $ruleValue;
                    } else {
                        $fieldRules[] = $ruleKey . ':' . $ruleValue;
                    }
                }
            }

            $rules[$key] = $fieldRules;
        }

        $data = $request->validate($rules);

        foreach ($workspaceFields as $field) {
            $key = 'field_'.$field->key;
            $value = $data[$key] ?? null;

            if ($field->type === 'boolean') {
                $value = (bool)($value ?? false);
            }

            if ($field->type === 'multiselect') {
                $value = array_values(array_filter($value ?? []));
            }

            if ($field->type === 'number' && $value !== null) {
                $value = $value + 0;
            }

            if ($value === null || $value === '' || $value === []) {
                if ($field->required) {
                    $extras[$field->key] = $value;
                }
            } else {
                $extras[$field->key] = $value;
            }

            unset($data[$key]);
        }

        $data['extras'] = $extras ?: null;

        return $data;
    }

    protected function resolveCompany(array $data): ?Company
    {
        $name = $data['company_name'] ?? null;

        if (! $name) {
            return null;
        }

        return Company::firstOrCreate(['name' => $name]);
    }

    protected function resolveContact(array $data, ?Company $company): ?Contact
    {
        $name = $data['contact_name'] ?? null;

        if (! $name) {
            return null;
        }

        return Contact::firstOrCreate(
            [
                'name' => $name,
                'company_id' => $company?->id,
            ],
            [
                'email' => $data['contact_email'] ?? null,
                'position' => $data['contact_position'] ?? null,
            ]
        );
    }

    protected function authorizeChat(CoffeeChat $chat): void
    {
        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        return [
            'planned' => 'Planned',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'follow_up_required' => 'Follow-up Required',
        ];
    }

    protected function optionValues($field)
    {
        return collect($field->options ?? [])->map(function ($option) {
            if (is_array($option)) {
                return (string) ($option['value'] ?? '');
            }

            return (string) $option;
        })->filter();
    }
}

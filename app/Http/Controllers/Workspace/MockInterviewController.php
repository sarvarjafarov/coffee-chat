<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\MockInterview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MockInterviewController extends Controller
{
    public function index(): View
    {
        $interviews = MockInterview::where('user_id', auth()->id())
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        $all = MockInterview::where('user_id', auth()->id())->get();

        $upcoming = $all->filter(function (MockInterview $interview) {
            return $interview->status === 'scheduled'
                && $interview->scheduled_at
                && $interview->scheduled_at->isFuture();
        })->count();

        $statusCounts = $all->groupBy('status')->map->count();

        return view('workspace.mock-interviews.index', [
            'interviews' => $interviews,
            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
            'totalInterviews' => $all->count(),
            'upcomingInterviews' => $upcoming,
            'completedInterviews' => $all->where('status', 'completed')->count(),
            'noShowInterviews' => $all->where('status', 'no_show')->count(),
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('workspace.mock-interviews.create', [
            'interview' => new MockInterview([
                'status' => 'scheduled',
                'duration_minutes' => 45,
                'reminder_channels' => $this->defaultReminderChannels(),
            ]),
            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        MockInterview::create([
            'user_id' => $request->user()->id,
            'interview_type' => $data['interview_type'],
            'difficulty' => $data['difficulty'] ?? null,
            'focus_area' => $data['focus_area'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'status' => $data['status'],
            'partner_name' => $data['partner_name'] ?? null,
            'partner_email' => $data['partner_email'] ?? null,
            'join_url' => $data['join_url'] ?? null,
            'agenda' => $data['agenda'] ?? null,
            'notes' => $data['notes'] ?? null,
            'feedback' => $data['feedback'] ?? null,
            'rating' => $data['rating'] ?? null,
            'reminder_channels' => $data['reminder_channels'] ?? $this->defaultReminderChannels(),
            'prep_materials' => $data['prep_materials'] ?? null,
        ]);

        return redirect()->route('workspace.mock-interviews.index')
            ->with('status', 'Mock interview saved.');
    }

    public function edit(MockInterview $mockInterview): View
    {
        $this->authorizeInterview($mockInterview);

        return view('workspace.mock-interviews.edit', [
            'interview' => $mockInterview,
            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function update(Request $request, MockInterview $mockInterview): RedirectResponse
    {
        $this->authorizeInterview($mockInterview);
        $data = $this->validated($request);

        $mockInterview->update([
            'interview_type' => $data['interview_type'],
            'difficulty' => $data['difficulty'] ?? null,
            'focus_area' => $data['focus_area'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'status' => $data['status'],
            'partner_name' => $data['partner_name'] ?? null,
            'partner_email' => $data['partner_email'] ?? null,
            'join_url' => $data['join_url'] ?? null,
            'agenda' => $data['agenda'] ?? null,
            'notes' => $data['notes'] ?? null,
            'feedback' => $data['feedback'] ?? null,
            'rating' => $data['rating'] ?? null,
            'reminder_channels' => $data['reminder_channels'] ?? $this->defaultReminderChannels(),
            'prep_materials' => $data['prep_materials'] ?? null,
        ]);

        return redirect()->route('workspace.mock-interviews.index')
            ->with('status', 'Mock interview updated.');
    }

    public function destroy(MockInterview $mockInterview): RedirectResponse
    {
        $this->authorizeInterview($mockInterview);
        $mockInterview->delete();

        return redirect()->route('workspace.mock-interviews.index')
            ->with('status', 'Mock interview removed.');
    }

    public function ics(MockInterview $mockInterview)
    {
        $this->authorizeInterview($mockInterview);

        abort_unless($mockInterview->scheduled_at, 404);

        $start = $mockInterview->scheduled_at->copy()->setTimezone('UTC');
        $end = $start->copy()->addMinutes($mockInterview->duration_minutes ?? 45);
        $title = ucfirst($mockInterview->interview_type).' mock interview';
        $summary = trim(($mockInterview->partner_name ? $mockInterview->partner_name.' — ' : '').$title);

        $descriptionParts = array_filter([
            $mockInterview->focus_area,
            $mockInterview->agenda,
            $mockInterview->join_url ? 'Join: '.$mockInterview->join_url : null,
        ]);

        $ics = "BEGIN:VCALENDAR\r\n".
            "VERSION:2.0\r\n".
            "PRODID:-//CoffeeChat OS//EN\r\n".
            "BEGIN:VEVENT\r\n".
            "UID:mock-{$mockInterview->id}@coffeechat-os\r\n".
            "DTSTAMP:".$start->format('Ymd\THis\Z')."\r\n".
            "DTSTART:".$start->format('Ymd\THis\Z')."\r\n".
            "DTEND:".$end->format('Ymd\THis\Z')."\r\n".
            "SUMMARY:".addcslashes($summary, ",;\\")."\r\n".
            ($mockInterview->join_url ? "LOCATION:".addcslashes($mockInterview->join_url, ",;\\")."\r\n" : '').
            ($descriptionParts ? "DESCRIPTION:".addcslashes(implode(' | ', $descriptionParts), ",;\\")."\r\n" : '').
            "END:VEVENT\r\nEND:VCALENDAR";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="mock-interview-'.$mockInterview->id.'.ics"',
        ]);
    }

    protected function validated(Request $request): array
    {
        $statusOptions = array_keys($this->statusOptions());
        $typeOptions = array_keys($this->typeOptions());
        $reminderChannels = $this->reminderChannelOptions();

        $rules = [
            'interview_type' => ['required', Rule::in($typeOptions)],
            'difficulty' => ['nullable', 'string', 'max:50'],
            'focus_area' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'time_zone' => ['nullable', 'string', 'max:64'],
            'duration_minutes' => ['nullable', 'integer', 'between:15,240'],
            'status' => ['required', Rule::in($statusOptions)],
            'partner_name' => ['nullable', 'string', 'max:255'],
            'partner_email' => ['nullable', 'email', 'max:255'],
            'join_url' => ['nullable', 'url', 'max:255'],
            'agenda' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'feedback' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'reminder_channels' => ['nullable', 'array'],
            'reminder_channels.*' => ['string', Rule::in($reminderChannels)],
            'prep_materials' => ['nullable', 'string'],
        ];

        $data = $request->validate($rules);

        if (! empty($data['reminder_channels'])) {
            $data['reminder_channels'] = collect($data['reminder_channels'])
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        return [
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'no_show' => 'No-show',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function typeOptions(): array
    {
        return [
            'case' => 'Case',
            'behavioral' => 'Behavioral',
            'product' => 'Product',
            'technical' => 'Technical',
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function defaultReminderChannels(): array
    {
        return ['email', 'push'];
    }

    /**
     * @return array<int, string>
     */
    protected function reminderChannelOptions(): array
    {
        return ['email', 'push'];
    }

    protected function authorizeInterview(MockInterview $mockInterview): void
    {
        if ($mockInterview->user_id !== auth()->id()) {
            abort(403);
        }
    }
}

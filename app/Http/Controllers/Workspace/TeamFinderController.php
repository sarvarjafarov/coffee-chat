<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\CoffeeChat;
use App\Models\Company;
use App\Models\Contact;
use App\Services\ContactDiscovery\ContactDiscoveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeamFinderController extends Controller
{
    /**
     * Display the team finder page with optional results.
     */
    public function index(Request $request): View
    {
        $input = $request->validate([
            'position' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'team_name' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = collect($input)
            ->map(static function ($value) {
                return is_string($value) ? trim($value) : $value;
            })
            ->toArray();

        $hasFilters = collect($filters)->filter(static fn ($value) => filled($value))->isNotEmpty();

        $results = collect();
        $recommended = collect();
        $summary = [
            'total' => 0,
            'by_source' => [],
        ];
        $diagnostics = collect();
        $message = null;

        $readyToSearch = filled($filters['company'] ?? null) && filled($filters['position'] ?? null);

        if ($hasFilters && $readyToSearch) {
            $discovery = new ContactDiscoveryService();
            $payload = $discovery->discover([
                'company' => $filters['company'] ?? null,
                'position' => $filters['position'] ?? null,
                'team' => $filters['team_name'] ?? null,
                'city' => $filters['city'] ?? null,
            ]);
            $results = collect($payload['results'] ?? []);
            $recommended = collect($payload['recommended'] ?? []);

            if ($recommended->isNotEmpty()) {
                $topIds = $recommended->pluck('uid')->filter();
                $results = $results->reject(function ($item) use ($topIds) {
                    return $topIds->contains($item['uid'] ?? null);
                })->values();
            }

            $summary = $payload['summary'];
            $diagnostics = collect($payload['diagnostics'] ?? []);
        } elseif ($hasFilters) {
            $message = 'Please provide both a company and a position to discover coffee chat matches.';
        }

        return view('workspace.team-finder.index', [
            'filters' => $filters,
            'hasFilters' => $hasFilters,
            'results' => $results,
            'recommended' => $recommended,
            'summary' => $summary,
            'diagnostics' => $diagnostics,
            'scrapeAttempted' => $hasFilters && $readyToSearch,
            'statusMessage' => $message,
        ]);
    }

    /**
     * Lightweight JSON search endpoint for the team finder experience.
     */
    public function teamFinder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'team' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = collect($validated)->map(fn ($value) => is_string($value) ? trim($value) : $value)->toArray();

        $discovery = new ContactDiscoveryService();
        $payload = $discovery->discover([
            'company' => $filters['company'] ?? null,
            'position' => $filters['position'] ?? null,
            'team' => $filters['team'] ?? null,
            'city' => $filters['city'] ?? null,
        ]);

        $recommended = collect($payload['recommended'] ?? []);
        $results = collect($payload['results'] ?? []);

        if ($recommended->isNotEmpty()) {
            $topIds = $recommended->pluck('uid')->filter();
            $results = $results->reject(function ($item) use ($topIds) {
                return $topIds->contains($item['uid'] ?? null);
            })->values();
        }

        return response()->json([
            'query' => $filters,
            'results' => $results,
            'recommended' => $recommended,
            'summary' => $payload['summary'] ?? [],
            'diagnostics' => $payload['diagnostics'] ?? [],
        ]);
    }

    public function follow(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $data = $request->validate([
            'contact' => ['required', 'string'],
        ]);

        $payload = json_decode($data['contact'], true);

        if (! is_array($payload)) {
            return back()->withErrors(['contact' => 'We could not process that lead.'])->withInput();
        }

        $name = trim((string) ($payload['name'] ?? ''));

        if ($name === '') {
            return back()->withErrors(['contact' => 'This lead is missing a name.'])->withInput();
        }

        $companyName = trim((string) ($payload['company'] ?? ''));
        $profileUrl = $payload['url'] ?? $payload['profile_url'] ?? null;

        DB::beginTransaction();

        try {
            $company = null;
            if ($companyName !== '') {
                $company = Company::firstOrCreate(['name' => $companyName]);
            }

            $contactQuery = Contact::query();

            if ($profileUrl) {
                $contactQuery->where('linkedin_url', $profileUrl);
            } else {
                $contactQuery->where('name', $name);
                if ($company) {
                    $contactQuery->where('company_id', $company->id);
                }
            }

            $contact = $contactQuery->first();

            if (! $contact) {
                $contact = Contact::create([
                    'company_id' => $company?->id,
                    'name' => $name,
                    'position' => $payload['role'] ?? null,
                    'email' => $payload['email'] ?? null,
                    'linkedin_url' => $profileUrl,
                    'location' => $payload['location'] ?? null,
                    'notes' => $payload['snippet'] ?? null,
                ]);
            } else {
                $contact->fill([
                    'company_id' => $contact->company_id ?: $company?->id,
                    'position' => $contact->position ?: ($payload['role'] ?? null),
                    'email' => $contact->email ?: ($payload['email'] ?? null),
                    'linkedin_url' => $contact->linkedin_url ?: $profileUrl,
                    'location' => $contact->location ?: ($payload['location'] ?? null),
                ])->save();
            }

            $chat = CoffeeChat::create([
                'user_id' => $user->id,
                'company_id' => $company?->id,
                'contact_id' => $contact->id,
                'position_title' => $payload['role'] ?? null,
                'status' => 'planned',
                'is_virtual' => true,
                'notes' => $this->formatFollowNotes($payload),
                'extras' => [
                    'team_finder_source' => $payload['source'] ?? null,
                ],
            ]);

            DB::commit();

            return redirect()
                ->route('workspace.coffee-chats.edit', $chat)
                ->with('status', 'Lead saved to your coffee chat flow.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            return back()->withErrors(['contact' => 'We could not save this lead.'])->withInput();
        }
    }

    protected function formatFollowNotes(array $payload): string
    {
        $lines = [];

        if (! empty($payload['source'])) {
            $lines[] = 'Source: '.Str::of($payload['source'])->replace('_', ' ')->title();
        }

        if (! empty($payload['snippet'])) {
            $lines[] = $payload['snippet'];
        }

        if (! empty($payload['url'])) {
            $lines[] = 'Profile: '.$payload['url'];
        }

        if (! empty($payload['location'])) {
            $lines[] = 'Location: '.$payload['location'];
        }

        return implode("\n", $lines);
    }

    /**
     * Quick-create a coffee chat for the selected contact.
     */
    public function storeCoffeeChat(Request $request, Contact $contact): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', Rule::in(array_keys($this->statusOptions()))],
            'position_title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_virtual' => ['nullable', 'boolean'],
            'scheduled_at' => ['nullable', 'date'],
            'time_zone' => ['nullable', 'string', 'max:64'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'between:5,480'],
        ]);

        $notes = $data['notes'] ?? null;

        if (! $notes) {
            $notes = sprintf(
                'Created via Team Finder on %s.',
                Carbon::now()->format('M j, Y')
            );
        }

        $chat = CoffeeChat::create([
            'user_id' => $request->user()->id,
            'company_id' => $contact->company_id,
            'contact_id' => $contact->id,
            'position_title' => $data['position_title'] ?? $contact->position,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => $data['status'] ?? 'planned',
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'is_virtual' => array_key_exists('is_virtual', $data) ? (bool) $data['is_virtual'] : true,
            'notes' => $notes,
        ]);

        return redirect()
            ->route('workspace.coffee-chats.edit', $chat)
            ->with('status', 'Coffee chat added to your flow. Personalise the details below.');
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
}

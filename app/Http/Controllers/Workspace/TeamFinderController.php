<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\CoffeeChat;
use App\Models\ScrapedContact;
use App\Services\LeadScraper\LeadScraperManager;
use App\View\Models\TeamFinderResult;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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

        $normalized = collect($filters)
            ->map(static function ($value) {
                if (!is_string($value) || $value === '') {
                    return null;
                }

                return mb_strtolower($value, 'UTF-8');
            })
            ->toArray();

        $positionFilter = $normalized['position'] ?? null;
        $companyFilter = $normalized['company'] ?? null;
        $cityFilter = $normalized['city'] ?? null;
        $teamFilter = $normalized['team_name'] ?? null;

        $hasFilters = collect($filters)->filter(static fn ($value) => filled($value))->isNotEmpty();

        $contacts = null;

        $teamNameColumnAvailable = Schema::hasColumn('contacts', 'team_name');

        $signature = $this->signature($normalized);

        if ($hasFilters) {
            $contacts = Contact::query()
                ->with([
                    'company',
                    'coffeeChats' => function ($query) use ($request): void {
                        $query->where('user_id', $request->user()->id)
                            ->latest('scheduled_at')
                            ->latest('created_at');
                    },
                ])
                ->when($positionFilter, static function ($query, string $position): void {
                    $query->whereRaw('LOWER(position) LIKE ?', ["%{$position}%"]);
                })
                ->when($companyFilter, static function ($query, string $companyName): void {
                    $query->whereHas('company', static function ($companyQuery) use ($companyName): void {
                        $companyQuery->whereRaw('LOWER(name) LIKE ?', ["%{$companyName}%"]);
                    });
                })
                ->when($cityFilter, static function ($query, string $city): void {
                    $query->whereRaw('LOWER(location) LIKE ?', ["%{$city}%"]);
                })
                ->when($teamFilter, static function ($query, string $teamName) use ($teamNameColumnAvailable): void {
                    if (! $teamNameColumnAvailable) {
                        return;
                    }

                    $query->whereRaw('LOWER(team_name) LIKE ?', ["%{$teamName}%"]);
                })
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString();

            $this->refreshScrapedContacts($normalized, $signature);
        }

        $scrapedContacts = $hasFilters
            ? ScrapedContact::where('search_signature', $signature)
                ->latest('scraped_at')
                ->take(25)
                ->get()
            : collect();

        $results = collect();

        if ($contacts) {
            $results = $contacts->getCollection()->map(fn ($contact) => new TeamFinderResult('contact', $contact));
        }

        $scrapedContacts->each(function (ScrapedContact $lead) use (&$results) {
            $results->push(new TeamFinderResult('scraped', null, [
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'name' => $lead->name,
                'position' => $lead->position,
                'company' => $lead->company,
                'team' => $lead->team,
                'email' => $lead->email,
                'profile_url' => $lead->profile_url,
                'location' => $lead->location,
                'source' => $lead->source,
                'scraped_at' => $lead->scraped_at,
            ]));
        });

        return view('workspace.team-finder.index', [
            'filters' => $filters,
            'contacts' => $contacts,
            'hasFilters' => $hasFilters,
            'teamNameColumnAvailable' => $teamNameColumnAvailable,
            'results' => $results,
            'scrapeAttempted' => $hasFilters,
        ]);
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

    protected function signature(array $normalized): string
    {
        return md5(json_encode($normalized));
    }

    protected function refreshScrapedContacts(array $normalized, string $signature): void
    {
        $latest = ScrapedContact::where('search_signature', $signature)
            ->orderByDesc('scraped_at')
            ->first();

        if ($latest && $latest->scraped_at && $latest->scraped_at->gt(now()->subMinutes(10))) {
            return;
        }

        $manager = new LeadScraperManager();
        $results = $manager->run($normalized);

        ScrapedContact::where('search_signature', $signature)->delete();

        $results->each(function (array $result) use ($signature): void {
            ScrapedContact::create([
                'search_signature' => $signature,
                'source' => $result['source'] ?? 'unknown',
                'first_name' => $result['first_name'] ?? null,
                'last_name' => $result['last_name'] ?? null,
                'name' => $result['name'] ?? null,
                'position' => $result['position'] ?? null,
                'company' => $result['company'] ?? null,
                'team' => $result['team'] ?? null,
                'email' => $result['email'] ?? null,
                'profile_url' => $result['profile_url'] ?? null,
                'location' => $result['location'] ?? null,
                'avatar_url' => $result['avatar_url'] ?? null,
                'metadata' => $result['metadata'] ?? null,
                'scraped_at' => now(),
            ]);
        });
    }
}

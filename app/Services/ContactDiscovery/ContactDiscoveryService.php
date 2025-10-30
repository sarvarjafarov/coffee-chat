<?php

namespace App\Services\ContactDiscovery;

use App\Models\Contact;
use App\Services\LeadScraper\LeadScraperManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ContactDiscoveryService
{
    public function __construct(
        protected ?LeadScraperManager $scraperManager = null
    ) {
        $this->scraperManager ??= new LeadScraperManager();
    }

    /**
     * Discover prospects using internal data and external providers.
     *
     * @param  array<string, mixed>  $filters
     * @return array{
     *     results: Collection<int, array<string, mixed>>,
     *     recommended: Collection<int, array<string, mixed>>,
     *     summary: array<string, mixed>
     * }
     */
    public function discover(array $filters): array
    {
        $normalized = $this->normalizeFilters($filters);

        $external = $this->scraperManager
            ->run($normalized)
            ->map(fn (array $result) => $this->transformResult($result, $normalized));

        $internal = $this->discoverInternal($normalized)
            ->map(fn (array $result) => $this->transformResult($result, $normalized));

        $diagnostics = $this->scraperManager->diagnostics();

        $combined = $external
            ->concat($internal)
            ->map(fn (array $result) => $this->applyScore($result, $normalized))
            ->filter(fn (array $result) => filled($result['name']))
            ->unique(fn (array $result) => $result['url'] ?? strtolower($result['name'].'|'.$result['company']))
            ->sortByDesc('score')
            ->values();

        return [
            'results' => $combined,
            'recommended' => $this->selectRecommendations($combined),
            'summary' => $this->buildSummary($combined),
            'diagnostics' => $diagnostics,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    protected function normalizeFilters(array $filters): array
    {
        return collect($filters)->map(function ($value) {
            if (is_string($value)) {
                return trim($value);
            }

            return $value;
        })->filter()->toArray();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, array<string, mixed>>
     */
    protected function discoverInternal(array $filters): Collection
    {
        if (! Schema::hasTable('contacts')) {
            return collect();
        }

        $companyFilter = strtolower((string) ($filters['company'] ?? ''));
        $positionFilter = strtolower((string) ($filters['position'] ?? ''));
        $teamFilter = strtolower((string) ($filters['team'] ?? ''));
        $cityFilter = strtolower((string) ($filters['city'] ?? ''));

        if ($companyFilter === '' && $positionFilter === '') {
            return collect();
        }

        $query = Contact::query()->with('company')->limit(15);

        if ($companyFilter !== '') {
            $query->whereHas('company', static function ($companyQuery) use ($companyFilter): void {
                $companyQuery->whereRaw('LOWER(name) LIKE ?', ["%{$companyFilter}%"]);
            });
        }

        if ($positionFilter !== '' && Schema::hasColumn('contacts', 'position')) {
            $query->whereRaw('LOWER(position) LIKE ?', ["%{$positionFilter}%"]);
        }

        if ($teamFilter !== '' && Schema::hasColumn('contacts', 'team_name')) {
            $query->whereRaw('LOWER(team_name) LIKE ?', ["%{$teamFilter}%"]);
        }

        if ($cityFilter !== '' && Schema::hasColumn('contacts', 'location')) {
            $query->whereRaw('LOWER(location) LIKE ?', ["%{$cityFilter}%"]);
        }

        return $query->get()->map(function (Contact $contact) use ($filters): array {
            return [
                'name' => $contact->name,
                'role' => $contact->position,
                'company' => $contact->company?->name ?? $filters['company'] ?? null,
                'team' => $contact->team_name,
                'location' => $contact->location,
                'url' => $contact->linkedin_url,
                'source' => 'workspace_directory',
                'confidence' => 0.75,
                'snippet' => Str::limit((string) $contact->notes, 140) ?: null,
                'raw' => [
                    'contact_id' => $contact->id,
                    'email' => $contact->email,
                ],
            ];
        });
    }

    /**
     * Normalise all providers into a consistent structure.
     *
     * @param  array<string, mixed>  $result
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    protected function transformResult(array $result, array $filters): array
    {
        $name = $result['name'] ?? trim(($result['first_name'] ?? '').' '.($result['last_name'] ?? ''));
        $role = $result['role'] ?? $result['position'] ?? data_get($result, 'metadata.position');
        $company = $result['company'] ?? data_get($result, 'metadata.company') ?? $filters['company'] ?? null;
        $team = $result['team'] ?? $result['team_name'] ?? data_get($result, 'metadata.team');
        $location = $result['location'] ?? data_get($result, 'metadata.location');
        $url = $result['url'] ?? $result['profile_url'] ?? data_get($result, 'metadata.profile_url');
        $source = $result['source'] ?? 'unknown';
        $confidence = $result['confidence'] ?? data_get($result, 'metadata.confidence');
        $snippet = $result['snippet'] ?? data_get($result, 'metadata.snippet');

        $confidence = $confidence !== null ? (float) $confidence : null;
        if ($confidence !== null && $confidence > 1.0) {
            $confidence /= 100;
        }

        return [
            'name' => $name ?: null,
            'role' => $role ?: null,
            'company' => $company ?: null,
            'team' => $team ?: null,
            'location' => $location ?: null,
            'url' => $url ?: null,
            'source' => $source,
            'confidence' => $confidence,
            'snippet' => $snippet ?: null,
            'raw' => $result,
            'uid' => $this->generateUid($result, $name, $company, $url),
        ];
    }

    protected function applyScore(array $result, array $filters): array
    {
        [$score, $breakdown] = $this->calculateScore($result, $filters);

        $result['score'] = $score;
        $result['score_breakdown'] = $breakdown;
        $result['primary_reason'] = $breakdown[0]['label'] ?? null;

        return $result;
    }

    /**
     * @param  array<string, mixed>  $result
     * @param  array<string, mixed>  $filters
     * @return array{0: float, 1: array<int, array<string, mixed>>}
     */
    protected function calculateScore(array $result, array $filters): array
    {
        $score = 0.0;
        $breakdown = [];
        $source = $result['source'] ?? 'unknown';

        if ($source === 'workspace_directory') {
            $score += 0.6;
            $breakdown[] = [
                'label' => 'Already in your workspace network',
                'value' => 0.6,
            ];
        } else {
            $confidence = $result['confidence'] ?? 0.35;
            $confidence = max(0.0, min(1.0, (float) $confidence));
            $contribution = round($confidence * 0.5, 2);
            $score += $contribution;
            $breakdown[] = [
                'label' => 'Profile confidence score',
                'value' => $contribution,
            ];
        }

        if (!empty($filters['position'])) {
            $position = strtolower($filters['position']);
            $role = strtolower((string) ($result['role'] ?? ''));
            if (str_contains($role, $position)) {
                $score += 0.2;
                $breakdown[] = [
                    'label' => 'Matches desired position keyword',
                    'value' => 0.2,
                ];
            }
        }

        if (!empty($filters['company'])) {
            $companyFilter = strtolower($filters['company']);
            $companyValue = strtolower((string) ($result['company'] ?? ''));
            $url = strtolower((string) ($result['url'] ?? ''));
            if (str_contains($companyValue, $companyFilter) || str_contains($url, Str::slug($companyFilter))) {
                $score += 0.15;
                $breakdown[] = [
                    'label' => 'Company alignment',
                    'value' => 0.15,
                ];
            }
        }

        if (!empty($filters['team'])) {
            $teamFilter = strtolower($filters['team']);
            $teamValue = strtolower((string) ($result['team'] ?? ''));
            if ($teamValue !== '' && str_contains($teamValue, $teamFilter)) {
                $score += 0.07;
                $breakdown[] = [
                    'label' => 'Team/department relevance',
                    'value' => 0.07,
                ];
            }
        }

        if (!empty($filters['city'])) {
            $cityFilter = strtolower($filters['city']);
            $location = strtolower((string) ($result['location'] ?? ''));
            if ($location !== '' && str_contains($location, $cityFilter)) {
                $score += 0.05;
                $breakdown[] = [
                    'label' => 'Location proximity',
                    'value' => 0.05,
                ];
            }
        }

        $score = min(1.0, round($score, 2));

        return [$score, $breakdown];
    }

    protected function selectRecommendations(Collection $results): Collection
    {
        if ($results->isEmpty()) {
            return collect();
        }

        $topInternal = $results->where('source', 'workspace_directory')->take(2);
        $remainingSlots = max(0, 5 - $topInternal->count());
        $topExternal = $results->where('source', '!=', 'workspace_directory')->take($remainingSlots);

        return $topInternal->concat($topExternal)->take(5)->values();
    }

    protected function buildSummary(Collection $results): array
    {
        $total = $results->count();

        return [
            'total' => $total,
            'by_source' => $results->groupBy('source')->map->count()->sortDesc()->toArray(),
        ];
    }

    protected function generateUid(array $result, ?string $name, ?string $company, ?string $url): string
    {
        if ($id = data_get($result, 'raw.contact_id')) {
            return 'workspace_'.$id;
        }

        if ($url) {
            return 'url_'.md5(strtolower($url));
        }

        return 'hash_'.md5(strtolower(($name ?? '').'|'.($company ?? '').'|'.data_get($result, 'source', 'unknown')));
    }
}

<?php

namespace App\Services\LeadScraper;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleCustomSearchScraper implements ScraperInterface
{
    public function sourceName(): string
    {
        return 'linkedin_public';
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array
    {
        $apiKey = config('services.google_search.key') ?: SiteSetting::value('google_search_api_key');
        $cseId = config('services.google_search.cx') ?: SiteSetting::value('google_cse_id');

        if (! $apiKey || ! $cseId) {
            return [];
        }

        $terms = collect([
            $filters['position'] ?? null,
            $filters['company'] ?? null,
            $filters['team'] ?? $filters['team_name'] ?? null,
            $filters['city'] ?? null,
        ])->filter()->implode(' ');

        if ($terms === '') {
            return [];
        }

        $query = Str::squish(trim('site:linkedin.com/in '.$terms));

        try {
            $response = Http::timeout(6)->get('https://www.googleapis.com/customsearch/v1', [
                'key' => $apiKey,
                'cx' => $cseId,
                'q' => $query,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('Google Custom Search request failed: '.$response->body());
            }

            return collect($response->json('items', []))
                ->map(function (array $item) use ($filters, $query) {
                    $title = data_get($item, 'title');
                    $snippet = data_get($item, 'snippet');
                    $link = data_get($item, 'link');

                    if (! $title && ! $link && ! $snippet) {
                        return null;
                    }

                    return [
                        'name' => $title,
                        'position' => $this->extractTitleFromSnippet($snippet),
                        'company' => $filters['company'] ?? null,
                        'team' => $filters['team'] ?? $filters['team_name'] ?? null,
                        'location' => $filters['city'] ?? null,
                        'profile_url' => $link,
                        'metadata' => [
                            'snippet' => $snippet,
                            'query' => $query,
                            'confidence' => 0.35,
                        ],
                    ];
                })
                ->filter()
                ->values()
                ->all();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    protected function extractTitleFromSnippet(?string $snippet): ?string
    {
        if (! $snippet) {
            return null;
        }

        $matches = [];
        if (preg_match('/(?:(?:works|worked)\s+as|role\s+as|position\s+as)\s+([^.,]+)/i', $snippet, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}

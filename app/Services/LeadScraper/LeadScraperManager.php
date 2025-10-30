<?php

namespace App\Services\LeadScraper;

use Illuminate\Support\Collection;

class LeadScraperManager
{
    /**
     * @var array<int, ScraperInterface>
     */
    protected array $scrapers = [];

    protected array $diagnostics = [];

    public function __construct()
    {
        $this->scrapers = [
            new PeopleDataLabsScraper(),
            new GoogleCustomSearchScraper(),
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function run(array $filters): Collection
    {
        $results = collect();
        $this->diagnostics = [];

        foreach ($this->scrapers as $scraper) {
            try {
                $scraped = $scraper->search($filters);
                $count = is_countable($scraped) ? count($scraped) : 0;
                $this->diagnostics[] = [
                    'source' => $scraper->sourceName(),
                    'status' => $count > 0 ? 'success' : 'no_results',
                    'count' => $count,
                    'message' => $count > 0 ? null : 'No matches returned for this query.',
                ];
                foreach ($scraped as $result) {
                    $results->push(array_merge($result, [
                        'source' => $scraper->sourceName(),
                    ]));
                }
            } catch (\Throwable $exception) {
                report($exception);
                $this->diagnostics[] = [
                    'source' => $scraper->sourceName(),
                    'status' => 'error',
                    'count' => 0,
                    'message' => $exception->getMessage(),
                ];
            }
        }

        return $results;
    }

    public function diagnostics(): array
    {
        return $this->diagnostics;
    }
}

<?php

namespace App\Services\LeadScraper;

use Illuminate\Support\Collection;

class LeadScraperManager
{
    /**
     * @var array<int, ScraperInterface>
     */
    protected array $scrapers = [];

    public function __construct()
    {
        $this->scrapers = [
            new PeopleDataLabsScraper(),
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function run(array $filters): Collection
    {
        $results = collect();

        foreach ($this->scrapers as $scraper) {
            try {
                $scraped = $scraper->search($filters);
                foreach ($scraped as $result) {
                    $results->push(array_merge($result, [
                        'source' => $scraper->sourceName(),
                    ]));
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $results;
    }
}

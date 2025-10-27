<?php

namespace App\Jobs;

use App\Models\ScrapedContact;
use App\Services\LeadScraper\LeadScraperManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ScrapeTeamMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(protected array $filters, protected string $signature)
    {
    }

    public function handle(): void
    {
        $manager = new LeadScraperManager();
        $results = $manager->run($this->filters);

        $results->each(function (array $result): void {
            ScrapedContact::updateOrCreate(
                [
                    'search_signature' => $this->signature,
                    'source' => $result['source'] ?? 'unknown',
                    'profile_url' => $result['profile_url'] ?? null,
                    'email' => $result['email'] ?? null,
                ],
                [
                    'name' => $result['name'] ?? null,
                    'position' => $result['position'] ?? null,
                    'company' => $result['company'] ?? null,
                    'location' => $result['location'] ?? null,
                    'avatar_url' => $result['avatar_url'] ?? null,
                    'metadata' => $result['metadata'] ?? null,
                    'scraped_at' => Carbon::now(),
                ]
            );
        });
    }
}

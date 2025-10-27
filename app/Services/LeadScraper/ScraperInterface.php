<?php

namespace App\Services\LeadScraper;

interface ScraperInterface
{
    /**
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array;

    public function sourceName(): string;
}

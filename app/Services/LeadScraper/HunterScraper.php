<?php

namespace App\Services\LeadScraper;

use Illuminate\Support\Str;

class HunterScraper implements ScraperInterface
{
    public function sourceName(): string
    {
        return 'hunter';
    }

    public function search(array $filters): array
    {
        $domain = Str::of($filters['company'] ?? 'example')->slug('-').'.com';
        $firstName = 'Jordan';
        $lastName = 'Lee';

        return [
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $firstName.' '.$lastName,
                'position' => $filters['position'] ?? 'Lead',
                'company' => $filters['company'] ?? 'Example Inc.',
                'team' => $filters['team_name'] ?? null,
                'email' => Str::lower($firstName.'.'.$lastName).'@'.$domain,
                'profile_url' => null,
                'location' => $filters['city'] ?? 'Remote',
                'metadata' => [
                    'confidence' => 62,
                    'note' => 'Sample dataset; connect Hunter API via Roach for production.'
                ],
            ],
        ];
    }
}

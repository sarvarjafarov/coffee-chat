<?php

namespace App\Services\LeadScraper;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PeopleDataLabsScraper implements ScraperInterface
{
    public function sourceName(): string
    {
        return 'people_data_labs';
    }

    public function search(array $filters): array
    {
        $apiKey = config('services.people_data_labs.api_key');

        if (! $apiKey) {
            return [];
        }

        $mustClauses = [];

        if (! empty($filters['position'])) {
            $mustClauses[] = [
                'match_phrase' => [
                    'job_title' => $filters['position'],
                ],
            ];
        }

        if (! empty($filters['company'])) {
            $mustClauses[] = [
                'match_phrase' => [
                    'job_company_name' => $filters['company'],
                ],
            ];
        }

        if (! empty($filters['city'])) {
            $mustClauses[] = [
                'match_phrase' => [
                    'location_name' => $filters['city'],
                ],
            ];
        }

        $teamValue = $filters['team_name'] ?? $filters['team'] ?? null;
        if (! empty($teamValue)) {
            $mustClauses[] = [
                'match_phrase' => [
                    'job_department' => $teamValue,
                ],
            ];
        }

        $query = $mustClauses
            ? ['bool' => ['must' => $mustClauses]]
            : ['match_all' => (object) []];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Api-Key' => $apiKey,
            ])->post('https://api.peopledatalabs.com/v5/person/search', [
                'query' => json_encode($query, JSON_UNESCAPED_UNICODE),
                'size' => 5,
                'dataset' => 'linkedin',
                'minimum_likelihood' => 1,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('People Data Labs request failed: '.$response->body());
            }

            $data = $response->json('data', []);

            return collect($data)->map(function (array $person) {
                $emails = collect($person['emails'] ?? [])->pluck('address')->filter();

                $location = collect([
                    data_get($person, 'location_city'),
                    data_get($person, 'location_region'),
                    data_get($person, 'location_country'),
                ])->filter()->implode(', ');

                return [
                    'first_name' => data_get($person, 'first_name'),
                    'last_name' => data_get($person, 'last_name'),
                    'name' => data_get($person, 'full_name'),
                    'position' => data_get($person, 'job_title'),
                    'company' => data_get($person, 'job_company_name'),
                    'team' => data_get($person, 'job_department'),
                    'email' => data_get($person, 'work_email') ?? $emails->first(),
                    'profile_url' => data_get($person, 'linkedin_url'),
                    'location' => $location,
                    'metadata' => [
                        'confidence' => data_get($person, 'confidence'),
                    ],
                ];
            })->filter(function (array $lead) {
                return filled($lead['name']) || filled($lead['email']);
            })->values()->all();
        } catch (\Throwable $exception) {
            report($exception);
            return [];
        }
    }
}

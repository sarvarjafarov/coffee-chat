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

        $clauses = [];

        if (! empty($filters['position'])) {
            $clauses[] = 'job_title:"'.addslashes($filters['position']).'"';
        }

        if (! empty($filters['company'])) {
            $clauses[] = 'job_company_name:"'.addslashes($filters['company']).'"';
        }

        if (! empty($filters['city'])) {
            $clauses[] = 'location_name:"'.addslashes($filters['city']).'"';
        }

        if (! empty($filters['team_name'])) {
            $clauses[] = 'job_department:"'.addslashes($filters['team_name']).'"';
        }

        $query = $clauses ? implode(' AND ', $clauses) : '*';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Api-Key' => $apiKey,
            ])->post('https://api.peopledatalabs.com/v5/person/search', [
                'query' => $query,
                'size' => 5,
                'dataset' => 'linkedin',
                'minimum_likelihood' => 1,
            ]);

            if ($response->failed()) {
                report(new \RuntimeException('People Data Labs request failed: '.$response->body()));
                return [];
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

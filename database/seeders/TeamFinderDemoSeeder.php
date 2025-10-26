<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class TeamFinderDemoSeeder extends Seeder
{
    /**
     * Seed curated contacts so the workspace team finder returns meaningful demo results.
     */
    public function run(): void
    {
        $contacts = [
            [
                'company' => [
                    'name' => 'Google',
                    'location' => 'Mountain View, CA',
                    'website' => 'https://www.google.com',
                    'linkedin_url' => 'https://www.linkedin.com/company/google/',
                ],
                'contact' => [
                    'name' => 'Priya Natarajan',
                    'position' => 'Senior Product Manager',
                    'team_name' => 'YouTube Creator Experience',
                    'email' => 'priya.natarajan@google.com',
                    'location' => 'New York, USA',
                    'linkedin_url' => 'https://www.linkedin.com/in/priya-natarajan-product/',
                ],
            ],
            [
                'company' => [
                    'name' => 'Google',
                ],
                'contact' => [
                    'name' => 'Marcus Chen',
                    'position' => 'Product Manager',
                    'team_name' => 'YouTube Ads',
                    'email' => 'marcus.chen@google.com',
                    'location' => 'New York, USA',
                    'linkedin_url' => 'https://www.linkedin.com/in/marcus-chen-pm/',
                ],
            ],
            [
                'company' => [
                    'name' => 'Amazon',
                    'location' => 'Seattle, WA',
                    'website' => 'https://www.amazon.com',
                    'linkedin_url' => 'https://www.linkedin.com/company/amazon/',
                ],
                'contact' => [
                    'name' => 'Ana Velásquez',
                    'position' => 'Principal Product Marketing Manager',
                    'team_name' => 'Prime Video Growth',
                    'email' => 'ana.velasquez@amazon.com',
                    'location' => 'New York, USA',
                    'linkedin_url' => 'https://www.linkedin.com/in/ana-velasquez-pmm/',
                ],
            ],
            [
                'company' => [
                    'name' => 'Amazon',
                ],
                'contact' => [
                    'name' => 'Daniela Rossi',
                    'position' => 'Product Marketing Manager',
                    'team_name' => 'Amazon Music Partnerships',
                    'email' => 'daniela.rossi@amazon.com',
                    'location' => 'Austin, USA',
                    'linkedin_url' => 'https://www.linkedin.com/in/daniela-rossi/',
                ],
            ],
            [
                'company' => [
                    'name' => 'Meta',
                    'location' => 'Menlo Park, CA',
                ],
                'contact' => [
                    'name' => 'Leah Wright',
                    'position' => 'Product Manager',
                    'team_name' => 'Instagram Reels Discovery',
                    'email' => 'leah.wright@meta.com',
                    'location' => 'New York, USA',
                    'linkedin_url' => 'https://www.linkedin.com/in/leah-wright-product/',
                ],
            ],
        ];

        foreach ($contacts as $entry) {
            $companyData = $entry['company'];
            $company = Company::firstOrCreate(
                ['name' => $companyData['name']],
                $companyData
            );

            $contactData = array_merge($entry['contact'], [
                'company_id' => $company->id,
            ]);

            Contact::updateOrCreate(
                ['email' => $contactData['email']],
                $contactData
            );
        }
    }
}


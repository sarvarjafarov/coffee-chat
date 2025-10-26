<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            ['slug' => 'email', 'label' => 'Email'],
            ['slug' => 'linkedin', 'label' => 'LinkedIn'],
            ['slug' => 'twitter', 'label' => 'Twitter / X'],
            ['slug' => 'referral', 'label' => 'Referral'],
            ['slug' => 'event', 'label' => 'Networking Event'],
            ['slug' => 'cold-call', 'label' => 'Cold Call'],
            ['slug' => 'slack', 'label' => 'Slack / Community'],
            ['slug' => 'other', 'label' => 'Other'],
        ];

        foreach ($channels as $channel) {
            Channel::query()->updateOrCreate(
                ['slug' => $channel['slug']],
                ['label' => $channel['label'], 'description' => $channel['label']]
            );
        }
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Carbon;

class AchievementService
{
    public function __construct(protected CoffeeChatProgressService $progressService)
    {
    }

    /**
     * Evaluate user metrics and unlock new achievements.
     *
     * @return array<int, array{slug:string,title:string,description:string}>
     */
    public function evaluateAndUnlock(User $user): array
    {
        $metrics = $this->progressService->metrics($user);
        $unlocked = UserAchievement::query()
            ->where('user_id', $user->id)
            ->pluck('slug')
            ->all();

        $new = [];

        foreach ($this->definitions() as $definition) {
            if (in_array($definition['slug'], $unlocked, true)) {
                continue;
            }

            $conditionMet = $definition['condition']($metrics);
            if (! $conditionMet) {
                continue;
            }

            $achievement = UserAchievement::create([
                'user_id' => $user->id,
                'slug' => $definition['slug'],
                'title' => $definition['title'],
                'description' => $definition['description'],
                'unlocked_at' => Carbon::now(),
            ]);

            $new[] = [
                'slug' => $achievement->slug,
                'title' => $achievement->title,
                'description' => $achievement->description,
            ];
        }

        return $new;
    }

    /**
     * Get all unlocked achievements for a user.
     */
    public function unlocked(User $user)
    {
        return UserAchievement::query()
            ->where('user_id', $user->id)
            ->orderByDesc('unlocked_at')
            ->get();
    }

    /**
     * Achievement definitions.
     *
     * @return array<int, array{slug:string,title:string,description:string,condition:callable}>
     */
    protected function definitions(): array
    {
        return [
            [
                'slug' => 'chats_10',
                'title' => 'First 10 chats',
                'description' => 'Logged 10 completed coffee chats.',
                'condition' => fn ($m) => ($m['total_completed'] ?? 0) >= 10,
            ],
            [
                'slug' => 'chats_25',
                'title' => '25 chats',
                'description' => 'Momentum builder with 25 completed chats.',
                'condition' => fn ($m) => ($m['total_completed'] ?? 0) >= 25,
            ],
            [
                'slug' => 'chats_50',
                'title' => '50 chats',
                'description' => 'Master connector with 50 completed chats.',
                'condition' => fn ($m) => ($m['total_completed'] ?? 0) >= 50,
            ],
            [
                'slug' => 'streak_7',
                'title' => '7-day streak',
                'description' => 'A full week of consistent coffee chats.',
                'condition' => fn ($m) => ($m['current_streak'] ?? 0) >= 7 || ($m['longest_streak'] ?? 0) >= 7,
            ],
            [
                'slug' => 'streak_30',
                'title' => '30-day streak',
                'description' => 'Thirty days of consistent outreach.',
                'condition' => fn ($m) => ($m['current_streak'] ?? 0) >= 30 || ($m['longest_streak'] ?? 0) >= 30,
            ],
            [
                'slug' => 'weekly_goal',
                'title' => 'Weekly goal achieved',
                'description' => 'Hit your weekly coffee chat target.',
                'condition' => fn ($m) => ($m['weekly_goal'] ?? 0) > 0 && ($m['weekly_remaining'] ?? 1) === 0,
            ],
            [
                'slug' => 'level_5',
                'title' => 'Level 5',
                'description' => 'Reached XP level 5.',
                'condition' => fn ($m) => ($m['level'] ?? 1) >= 5,
            ],
            [
                'slug' => 'level_10',
                'title' => 'Level 10',
                'description' => 'Reached XP level 10.',
                'condition' => fn ($m) => ($m['level'] ?? 1) >= 10,
            ],
        ];
    }
}

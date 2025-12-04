<?php

namespace App\Services;

use App\Models\CoffeeChat;
use App\Models\User;
use Illuminate\Support\Carbon;

class CoffeeChatProgressService
{
    /**
     * Apply completion timestamps and XP bookkeeping based on status changes.
     *
     * @param  CoffeeChat|null  $existing
     * @param  array<string, mixed>  $payload
     * @return array{0: array<string, mixed>, 1: int}
     */
    public function applyCompletionState(?CoffeeChat $existing, array $payload, ?User $user = null): array
    {
        $mergedExtras = array_replace($existing?->extras ?? [], $payload['extras'] ?? []);

        $wasCompleted = $existing?->status === 'completed';
        $isCompleted = ($payload['status'] ?? null) === 'completed';

        $existingCompletedAt = $existing?->completed_at;
        $awardedXp = (int) ($mergedExtras['xp_awarded'] ?? 0);
        $xpDelta = 0;

        if ($isCompleted) {
            $payload['completed_at'] = $payload['completed_at'] ?? $existingCompletedAt ?? now();

            if (! $wasCompleted) {
                $awardedXp = $this->calculateXp($payload);
                $mergedExtras['xp_awarded'] = $awardedXp;
                $xpDelta += $awardedXp;
            } elseif (! array_key_exists('xp_awarded', $mergedExtras)) {
                $awardedXp = $this->calculateXp($payload);
                $mergedExtras['xp_awarded'] = $awardedXp;
                $xpDelta += $awardedXp;
            }
        } else {
            if ($wasCompleted) {
                $payload['completed_at'] = null;

                if ($awardedXp > 0) {
                    $xpDelta -= $awardedXp;
                }

                unset($mergedExtras['xp_awarded']);
            }
        }

        $payload['extras'] = $mergedExtras ?: null;

        return [$payload, $xpDelta];
    }

    public function applyXpDelta(User $user, int $xpDelta): void
    {
        if ($xpDelta === 0) {
            return;
        }

        $newTotal = max(0, (int) ($user->xp_total ?? 0) + $xpDelta);

        $user->forceFill(['xp_total' => $newTotal])->save();
    }

    /**
     * Calculate XP for a completed chat based on provided payload.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function calculateXp(array $payload): int
    {
        $base = 10;
        $bonus = 0;

        if (! empty($payload['summary']) || ! empty($payload['notes']) || ! empty($payload['next_steps'])) {
            $bonus += 5;
        }

        if (! empty($payload['duration_minutes']) && (int) $payload['duration_minutes'] >= 45) {
            $bonus += 2;
        }

        return $base + $bonus;
    }

    /**
     * Gather streak, recency, and goal metrics for a user.
     *
     * @return array<string, int|float>
     */
    public function metrics(User $user): array
    {
        $now = Carbon::now(config('app.timezone'));
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();

        $completed = CoffeeChat::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get(['completed_at']);

        $uniqueDays = $completed->pluck('completed_at')
            ->filter()
            ->map(fn ($dt) => $dt->copy()->setTimezone($now->timezone)->startOfDay())
            ->unique(function (Carbon $dt) {
                return $dt->format('Y-m-d');
            })
            ->sort()
            ->values();

        [$currentStreak, $longestStreak] = $this->calculateStreaks($uniqueDays, $now->copy()->startOfDay());

        $last7 = $completed->filter(fn ($dt) => $dt->copy()->setTimezone($now->timezone)->greaterThanOrEqualTo($now->copy()->subDays(6)->startOfDay()))->count();
        $last30 = $completed->filter(fn ($dt) => $dt->copy()->setTimezone($now->timezone)->greaterThanOrEqualTo($now->copy()->subDays(29)->startOfDay()))->count();
        $totalCompleted = $completed->count();

        $weeklyCompleted = $completed->filter(function ($dt) use ($weekStart, $weekEnd) {
            $date = $dt->copy()->setTimezone($weekStart->timezone);
            return $date->betweenIncluded($weekStart, $weekEnd);
        })->count();

        $weeklyGoal = max(0, (int) ($user->weekly_chat_goal ?? 3));
        $weeklyRemaining = max(0, $weeklyGoal - $weeklyCompleted);
        $weeklyProgress = $weeklyGoal > 0 ? min(1, $weeklyCompleted / $weeklyGoal) : 0;

        $xpTotal = (int) ($user->xp_total ?? 0);
        $levelSize = 100;
        $level = (int) floor($xpTotal / $levelSize) + 1;
        $levelProgress = ($xpTotal % $levelSize) / $levelSize;

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'last_7_days' => $last7,
            'last_30_days' => $last30,
            'weekly_completed' => $weeklyCompleted,
            'weekly_goal' => $weeklyGoal,
            'weekly_remaining' => $weeklyRemaining,
            'weekly_progress' => $weeklyProgress,
            'xp_total' => $xpTotal,
            'level' => $level,
            'level_progress' => $levelProgress,
            'total_completed' => $totalCompleted,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Carbon>  $days
     * @return array{0:int,1:int}
     */
    protected function calculateStreaks($days, Carbon $today): array
    {
        $current = 0;
        $longest = 0;

        $days = $days->sort()->values();

        $run = 0;
        $prev = null;
        foreach ($days as $day) {
            if ($prev === null) {
                $run = 1;
            } else {
                $diff = $day->diffInDays($prev);
                if ($diff === 1) {
                    $run += 1;
                } else {
                    $run = 1;
                }
            }

            $longest = max($longest, $run);
            $prev = $day;
        }

        if ($days->isNotEmpty()) {
            $latest = $days->last();

            if ($today->diffInDays($latest) === 0) {
                $current = 1;
                for ($i = $days->count() - 2; $i >= 0; $i--) {
                    $diff = $latest->diffInDays($days[$i]);
                    if ($diff === 1) {
                        $current += 1;
                        $latest = $days[$i];
                    } else {
                        break;
                    }
                }
            } elseif ($today->diffInDays($latest) === 1) {
                $current = 1;
                for ($i = $days->count() - 2; $i >= 0; $i--) {
                    $diff = $latest->diffInDays($days[$i]);
                    if ($diff === 1) {
                        $current += 1;
                        $latest = $days[$i];
                    } else {
                        break;
                    }
                }
            }
        }

        return [$current, $longest];
    }
}

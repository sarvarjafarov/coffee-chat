<?php

namespace App\Console\Commands;

use App\Models\MarketingAttribution;
use App\Models\MarketingEvent;
use App\Models\MarketingTouchpoint;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ComputeMarketingAttribution extends Command
{
    protected $signature = 'marketing:attribution {--days=30 : Lookback window in days} {--half-life=7 : Half-life for time-decay weighting}';

    protected $description = 'Compute first-touch, last-touch, linear, and time-decay attribution for recent conversions.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $halfLife = max(1, (int) $this->option('half-life'));
        $cutoff = now()->subDays($days);

        $conversions = MarketingEvent::query()
            ->where('occurred_at', '>=', $cutoff)
            ->whereIn('event_name', ['auth_submit', 'coffee_chat_submit', 'team_finder_follow'])
            ->get();

        $total = $conversions->count();
        $this->info("Processing {$total} conversion events...");

        $processed = 0;
        foreach ($conversions as $event) {
            $conversionType = $this->conversionType($event);
            if (! $conversionType) {
                continue;
            }

            $touchpoints = MarketingTouchpoint::query()
                ->where(function ($query) use ($event) {
                    if ($event->user_id) {
                        $query->orWhere('user_id', $event->user_id);
                    }
                    $query->orWhere('session_id', $event->session_id);
                })
                ->where('created_at', '<=', $event->occurred_at)
                ->orderBy('created_at')
                ->get();

            if ($touchpoints->isEmpty()) {
                continue;
            }

            MarketingAttribution::where('marketing_event_id', $event->id)->delete();

            $models = [
                'first_touch' => $this->firstTouch($touchpoints),
                'last_touch' => $this->lastTouch($touchpoints),
                'linear' => $this->linear($touchpoints),
                'time_decay' => $this->timeDecay($touchpoints, $event->occurred_at, $halfLife),
            ];

            foreach ($models as $model => $allocations) {
                foreach ($allocations as $touch) {
                    MarketingAttribution::create([
                        'marketing_event_id' => $event->id,
                        'conversion_type' => $conversionType,
                        'model' => $model,
                        'session_id' => $event->session_id,
                        'user_id' => $event->user_id,
                        'source' => $touch['source'],
                        'medium' => $touch['medium'],
                        'campaign' => $touch['campaign'],
                        'credit' => $touch['credit'],
                        'occurred_at' => $event->occurred_at,
                    ]);
                }
            }

            $processed++;
        }

        $this->info("Attribution computed for {$processed} conversions.");

        return Command::SUCCESS;
    }

    protected function conversionType(MarketingEvent $event): ?string
    {
        $context = $event->properties['context'] ?? null;

        return match ($event->event_name) {
            'auth_submit' => $context === 'register' ? 'signup' : null,
            'coffee_chat_submit' => $context === 'create' ? 'coffee_chat_created' : null,
            'team_finder_follow' => 'team_finder_follow',
            default => null,
        };
    }

    protected function firstTouch(Collection $touchpoints): array
    {
        $tp = $touchpoints->first();

        return $tp ? [array_merge($this->touchData($tp), ['credit' => 1])] : [];
    }

    protected function lastTouch(Collection $touchpoints): array
    {
        $tp = $touchpoints->last();

        return $tp ? [array_merge($this->touchData($tp), ['credit' => 1])] : [];
    }

    protected function linear(Collection $touchpoints): array
    {
        $count = $touchpoints->count();
        if ($count === 0) {
            return [];
        }

        $credit = 1 / $count;

        return $touchpoints->map(fn ($tp) => array_merge($this->touchData($tp), ['credit' => $credit]))->all();
    }

    protected function timeDecay(Collection $touchpoints, Carbon $conversionTime, int $halfLifeDays): array
    {
        $weights = $touchpoints->map(function ($tp) use ($conversionTime, $halfLifeDays) {
            $days = max(0, $tp->created_at->diffInDays($conversionTime));
            $weight = pow(0.5, $days / $halfLifeDays);
            return ['tp' => $tp, 'weight' => $weight];
        });

        $totalWeight = $weights->sum('weight');
        if ($totalWeight <= 0) {
            return [];
        }

        return $weights->map(function ($item) use ($totalWeight) {
            /** @var \App\Models\MarketingTouchpoint $tp */
            $tp = $item['tp'];
            $weight = $item['weight'] / $totalWeight;

            return array_merge($this->touchData($tp), ['credit' => $weight]);
        })->all();
    }

    protected function touchData($tp): array
    {
        return [
            'source' => $tp->source ?? 'direct',
            'medium' => $tp->medium ?? 'none',
            'campaign' => $tp->campaign ?? null,
        ];
    }
}

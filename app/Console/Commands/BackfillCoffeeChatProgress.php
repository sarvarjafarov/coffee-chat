<?php

namespace App\Console\Commands;

use App\Models\CoffeeChat;
use App\Models\User;
use App\Services\CoffeeChatProgressService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BackfillCoffeeChatProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coffee-chats:backfill-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill completed_at, per-chat XP, and user xp_total for existing coffee chats.';

    public function __construct(protected CoffeeChatProgressService $progressService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting backfill for coffee chats...');

        $xpTotals = [];

        CoffeeChat::query()
            ->with('user')
            ->where('status', 'completed')
            ->orderBy('id')
            ->chunkById(200, function ($chats) use (&$xpTotals) {
                /** @var CoffeeChat $chat */
                foreach ($chats as $chat) {
                    $extras = $chat->extras ?? [];

                    if (empty($chat->completed_at)) {
                        $chat->completed_at = $chat->updated_at ?? now();
                    }

                    if (! isset($extras['xp_awarded'])) {
                        $awarded = $this->progressService->applyCompletionState($chat, [
                            'status' => 'completed',
                            'completed_at' => $chat->completed_at,
                            'summary' => $chat->summary,
                            'notes' => $chat->notes,
                            'next_steps' => $chat->next_steps,
                            'duration_minutes' => $chat->duration_minutes,
                            'extras' => $extras,
                        ])[0]['extras']['xp_awarded'] ?? 0;

                        $extras['xp_awarded'] = $awarded;
                        $chat->extras = $extras ?: null;
                    }

                    $awardedXp = (int) ($chat->extras['xp_awarded'] ?? 0);

                    $chat->save();

                    if ($awardedXp > 0 && $chat->user_id) {
                        $xpTotals[$chat->user_id] = ($xpTotals[$chat->user_id] ?? 0) + $awardedXp;
                    }
                }
            });

        $this->info('Applying XP totals to users...');

        foreach ($xpTotals as $userId => $xp) {
            $user = User::find($userId);
            if (! $user) {
                continue;
            }

            $user->xp_total = max((int) ($user->xp_total ?? 0), 0) + $xp;
            $user->save();
        }

        $this->info('Backfill complete.');

        return Command::SUCCESS;
    }
}

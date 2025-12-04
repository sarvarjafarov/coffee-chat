<?php

namespace App\Console\Commands;

use App\Models\CoffeeChat;
use App\Notifications\CoffeeChatReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendCoffeeChatReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coffee-chats:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for coffee chats happening tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tomorrow = Carbon::now()->addDay();
        $start = $tomorrow->copy()->startOfDay();
        $end = $tomorrow->copy()->endOfDay();

        $this->components->info('Preparing reminder emails for coffee chats scheduled between '.$start.' and '.$end.'.');

        $count = 0;

        CoffeeChat::query()
            ->with(['user', 'contact', 'company'])
            ->where('status', 'planned')
            ->whereNull('reminder_sent_at')
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [$start, $end])
            ->chunkById(100, function ($chats) use (&$count): void {
                foreach ($chats as $chat) {
                    if (! $chat->user || ! $chat->user->email) {
                        continue;
                    }

                    $chat->user->notify(new CoffeeChatReminder($chat));
                    $chat->forceFill(['reminder_sent_at' => Carbon::now()])->save();
                    $count++;
                }
            });

        $this->components->info("Sent {$count} reminder email(s).");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Notifications;

use App\Models\CoffeeChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CoffeeChatReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected CoffeeChat $chat)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $chat = $this->chat->loadMissing(['company', 'contact']);

        $scheduledAt = $chat->scheduled_at ? $chat->scheduled_at->copy() : null;
        if ($scheduledAt && $chat->time_zone) {
            $scheduledAt->setTimezone($chat->time_zone);
        }

        $timeZone = $chat->time_zone ?: config('app.timezone');
        $formattedTime = $scheduledAt
            ? $scheduledAt->format('l, F j · g:i A ').$timeZone
            : 'Time not set yet';

        $who = trim(collect([$chat->contact?->name, $chat->company?->name])->filter()->implode(' — '));
        $subjectName = $who ?: 'your coffee chat';

        $actionUrl = route('workspace.coffee-chats.edit', $chat);

        return (new MailMessage)
            ->subject('Reminder: '.$subjectName.' is tomorrow')
            ->greeting('Heads up, you have a coffee chat tomorrow.')
            ->line('Here are the details:')
            ->line('Who: '.($who ?: 'Coffee chat contact TBD'))
            ->line('When: '.$formattedTime)
            ->line('Where: '.($chat->location ?: 'Not specified'))
            ->lineIf($chat->summary, 'Context: '.Str::of($chat->summary)->limit(140))
            ->action('Review the chat plan', $actionUrl)
            ->line('Prep, reschedule, or add notes so you can follow up with confidence.');
    }
}

<?php

use App\Console\Commands\SendCoffeeChatReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('coffee-chats:send-reminders', SendCoffeeChatReminders::class)
    ->purpose('Send reminder emails for coffee chats happening tomorrow.');

Schedule::command('coffee-chats:send-reminders')
    ->dailyAt('08:00')
    ->timezone(config('app.timezone'))
    ->description('Email users about coffee chats scheduled for tomorrow.');

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coffee_chats', function (Blueprint $table) {
            if (! Schema::hasColumn('coffee_chats', 'reminder_sent_at')) {
                $table->dateTime('reminder_sent_at')->nullable()->after('extras');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_chats', function (Blueprint $table) {
            if (Schema::hasColumn('coffee_chats', 'reminder_sent_at')) {
                $table->dropColumn('reminder_sent_at');
            }
        });
    }
};

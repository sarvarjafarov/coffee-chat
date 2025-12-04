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
            if (! Schema::hasColumn('coffee_chats', 'completed_at')) {
                $table->dateTime('completed_at')->nullable()->after('reminder_sent_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'xp_total')) {
                $table->unsignedInteger('xp_total')->default(0)->after('plan_expires_at');
            }

            if (! Schema::hasColumn('users', 'weekly_chat_goal')) {
                $table->unsignedTinyInteger('weekly_chat_goal')->default(3)->after('xp_total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_chats', function (Blueprint $table) {
            if (Schema::hasColumn('coffee_chats', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'xp_total')) {
                $table->dropColumn('xp_total');
            }

            if (Schema::hasColumn('users', 'weekly_chat_goal')) {
                $table->dropColumn('weekly_chat_goal');
            }
        });
    }
};

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
            if (! Schema::hasColumn('coffee_chats', 'extras')) {
                $table->json('extras')->nullable()->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_chats', function (Blueprint $table) {
            if (Schema::hasColumn('coffee_chats', 'extras')) {
                $table->dropColumn('extras');
            }
        });
    }
};

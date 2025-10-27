<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scraped_contacts', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('source');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('team')->nullable()->after('company');
        });
    }

    public function down(): void
    {
        Schema::table('scraped_contacts', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'team']);
        });
    }
};

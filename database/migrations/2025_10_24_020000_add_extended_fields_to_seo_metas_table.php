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
        Schema::table('seo_metas', function (Blueprint $table): void {
            $table->json('meta_tags')->nullable()->after('meta');
            $table->json('media')->nullable()->after('meta_tags');
            $table->json('schema')->nullable()->after('media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_metas', function (Blueprint $table): void {
            $table->dropColumn(['meta_tags', 'media', 'schema']);
        });
    }
};

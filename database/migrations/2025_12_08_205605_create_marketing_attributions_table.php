<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_event_id')->constrained('marketing_events')->cascadeOnDelete();
            $table->string('conversion_type')->index();
            $table->string('model')->index();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source')->nullable()->index();
            $table->string('medium')->nullable()->index();
            $table->string('campaign')->nullable()->index();
            $table->decimal('credit', 8, 4)->default(0);
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_attributions');
    }
};

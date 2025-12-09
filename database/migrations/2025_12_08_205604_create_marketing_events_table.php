<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_name')->index();
            $table->json('properties')->nullable();
            $table->string('path')->nullable();
            $table->text('referrer')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_events');
    }
};

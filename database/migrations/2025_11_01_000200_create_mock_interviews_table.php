<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('interview_type')->default('case');
            $table->string('difficulty')->nullable();
            $table->string('focus_area')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('time_zone', 64)->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('partner_name')->nullable();
            $table->string('partner_email')->nullable();
            $table->string('join_url')->nullable();
            $table->text('agenda')->nullable();
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->json('reminder_channels')->nullable();
            $table->text('prep_materials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_interviews');
    }
};

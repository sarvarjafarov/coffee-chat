<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('case_study_id')->nullable()->constrained()->nullOnDelete();
            $table->string('custom_title')->nullable();
            $table->string('status')->default('planned');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('time_zone', 64)->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->json('self_scores')->nullable();
            $table->text('reflection')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('llm_feedback_opt_in')->default(false);
            $table->text('llm_feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_sessions');
    }
};

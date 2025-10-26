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
        Schema::create('coffee_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('position_title')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('time_zone', 64)->nullable();
            $table->string('location')->nullable();
            $table->string('status', 40)->default('planned');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('is_virtual')->default(true);
            $table->text('summary')->nullable();
            $table->text('key_takeaways')->nullable();
            $table->text('next_steps')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffee_chats');
    }
};

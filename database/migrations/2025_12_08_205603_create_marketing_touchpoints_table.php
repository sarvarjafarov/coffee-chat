<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_touchpoints', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source')->nullable()->index();
            $table->string('medium')->nullable()->index();
            $table->string('campaign')->nullable()->index();
            $table->string('content')->nullable();
            $table->string('term')->nullable();
            $table->text('referrer')->nullable();
            $table->string('landing_page')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_touchpoints');
    }
};

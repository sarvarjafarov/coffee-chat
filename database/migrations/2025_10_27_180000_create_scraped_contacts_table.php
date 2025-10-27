<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraped_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('search_signature')->index();
            $table->string('source');
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('profile_url')->nullable();
            $table->string('location')->nullable();
            $table->string('avatar_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('scraped_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_contacts');
    }
};

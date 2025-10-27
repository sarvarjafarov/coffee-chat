<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('publishable_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('price_id')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_settings');
    }
};

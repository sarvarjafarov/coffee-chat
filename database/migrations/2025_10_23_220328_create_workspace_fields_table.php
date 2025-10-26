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
        Schema::create('workspace_fields', function (Blueprint $table) {
            $table->id();
            $table->string('form')->default('coffee_chat');
            $table->string('key');
            $table->string('label');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('in_analytics')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->string('placeholder')->nullable();
            $table->string('help_text')->nullable();
            $table->json('options')->nullable();
            $table->json('validation')->nullable();
            $table->json('style')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['form', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_fields');
    }
};

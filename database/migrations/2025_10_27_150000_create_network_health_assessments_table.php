<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('network_health_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedSmallInteger('monthly_unique_contacts');
            $table->unsignedSmallInteger('warm_intros_last_quarter');
            $table->decimal('average_follow_up_days', 5, 2)->default(0);
            $table->unsignedTinyInteger('industry_diversity');
            $table->unsignedTinyInteger('relationship_strength');
            $table->unsignedSmallInteger('score');
            $table->text('summary')->nullable();
            $table->json('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('network_health_assessments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('case_type')->nullable();
            $table->string('industry')->nullable();
            $table->string('difficulty')->default('medium');
            $table->unsignedSmallInteger('estimated_duration_minutes')->nullable();
            $table->text('summary')->nullable();
            $table->text('prompt')->nullable();
            $table->json('exhibits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed a few starter cases so the workspace is not empty.
        $starterCases = [
            [
                'title' => 'Market sizing: Cold brew in NYC',
                'case_type' => 'market_sizing',
                'industry' => 'Food & Beverage',
                'difficulty' => 'medium',
                'estimated_duration_minutes' => 30,
                'summary' => 'Estimate the annual market size for cold brew coffee in New York City. Consider segments, pricing, and channel mix.',
            ],
            [
                'title' => 'Profitability: Regional gym chain',
                'case_type' => 'profitability',
                'industry' => 'Fitness',
                'difficulty' => 'medium',
                'estimated_duration_minutes' => 35,
                'summary' => 'Diagnose a profitability drop for a 12-location gym chain. Examine utilization, pricing, and fixed costs.',
            ],
            [
                'title' => 'Operations: Warehouse throughput',
                'case_type' => 'operations',
                'industry' => 'Logistics',
                'difficulty' => 'hard',
                'estimated_duration_minutes' => 40,
                'summary' => 'Improve throughput in a regional warehouse facing backlog. Identify constraints, staffing, and sequencing fixes.',
            ],
        ];

        foreach ($starterCases as $case) {
            $slug = Str::slug($case['title']);
            DB::table('case_studies')->insert([
                ...$case,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};

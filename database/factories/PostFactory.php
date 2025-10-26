<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'excerpt' => $this->faker->paragraph(),
            'body' => collect(range(1, 5))->map(fn () => $this->faker->paragraph())->join("\n\n"),
            'published_at' => now(),
            'is_published' => true,
        ];
    }

    public function draft(): self
    {
        return $this->state(function () {
            return [
                'is_published' => false,
                'published_at' => null,
            ];
        });
    }
}

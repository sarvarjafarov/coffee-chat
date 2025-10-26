<?php

namespace Database\Factories;

use App\Models\CoffeeChat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUpTask>
 */
class FollowUpTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coffee_chat_id' => CoffeeChat::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'notes' => $this->faker->optional()->paragraph(),
            'due_at' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'completed_at' => null,
            'reminder_sent_at' => null,
        ];
    }

    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}

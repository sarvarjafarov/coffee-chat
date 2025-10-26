<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoffeeChat>
 */
class CoffeeChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = $this->faker->dateTimeBetween('-2 months', '+2 months');
        $company = Company::factory();

        return [
            'user_id' => User::factory(),
            'company_id' => $company,
            'contact_id' => Contact::factory()->for($company),
            'position_title' => $this->faker->jobTitle(),
            'scheduled_at' => Carbon::instance($scheduledAt),
            'time_zone' => $this->faker->timezone(),
            'location' => $this->faker->city(),
            'status' => $this->faker->randomElement(['planned', 'completed', 'cancelled', 'follow_up_required']),
            'duration_minutes' => $this->faker->numberBetween(15, 60),
            'is_virtual' => $this->faker->boolean(70),
            'summary' => $this->faker->optional()->paragraph(),
            'key_takeaways' => $this->faker->optional()->paragraph(),
            'next_steps' => $this->faker->optional()->paragraph(),
            'notes' => $this->faker->optional()->paragraph(),
            'rating' => $this->faker->optional()->numberBetween(1, 5),
        ];
    }

    public function planned(): self
    {
        return $this->state(fn () => [
            'status' => 'planned',
            'scheduled_at' => now()->addDays(3),
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'scheduled_at' => now()->subDays(2),
            'rating' => $this->faker->numberBetween(3, 5),
        ]);
    }
}

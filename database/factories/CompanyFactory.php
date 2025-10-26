<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'industry' => $this->faker->randomElement([
                'Technology', 'Finance', 'Consulting', 'Healthcare', 'Education', 'Other',
            ]),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'website' => $this->faker->optional()->url(),
            'linkedin_url' => $this->faker->optional()->url(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'team_name' => $this->faker->optional()->randomElement([
                'Marketing Team',
                'Product Team',
                'Sales Team',
                'Operations Team',
                'Design Team',
                'Engineering Team',
                'People Ops',
                'Revenue Enablement',
            ]),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'linkedin_url' => $this->faker->optional()->url(),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrayerRequest>
 */
class PrayerRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prayer' => $this->faker->sentence(),
            'name' => $this->faker->name(),
            'is_prayed_for' => false,
            'prayed_at' => null,
        ];
    }

    /**
     * Indicate that the prayer request has been prayed for.
     */
    public function prayed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_prayed_for' => true,
            'prayed_at' => now(),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Enums\PrayerStatus;
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
            'status' => PrayerStatus::Received,
            'prayed_at' => null,
        ];
    }

    /**
     * Indicate that the prayer request has been prayed for.
     */
    public function prayed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PrayerStatus::Prayed,
            'prayed_at' => now(),
        ]);
    }

    /**
     * Indicate that the prayer request has been answered.
     */
    public function answered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PrayerStatus::Answered,
            'answered_at' => now(),
        ]);
    }
}

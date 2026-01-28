<?php

namespace Database\Factories;

use App\Models\PrayerRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'display_name' => $this->faker->name(),
            'is_public' => false,
            'is_approved' => false,
        ];
    }

    /**
     * Indicate that the testimonial is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the testimonial is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
            'is_approved' => true,
        ]);
    }

    /**
     * Associate with a prayer request.
     */
    public function withPrayerRequest(?PrayerRequest $prayerRequest = null): static
    {
        return $this->state(fn (array $attributes) => [
            'prayer_request_id' => $prayerRequest?->id ?? PrayerRequest::factory(),
        ]);
    }
}

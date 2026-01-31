<?php

namespace Database\Factories;

use App\Models\PrayerRequest;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Callback>
 */
class CallbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->randomElement(['+254', '0']).$this->faker->randomElement(['7', '1']).$this->faker->numerify('########'),
            'public_token' => Str::uuid()->toString(),
        ];
    }

    /**
     * Associate the callback with a service.
     */
    public function withService(?Service $service = null): static
    {
        return $this->state(fn (array $attributes) => [
            'service_id' => $service?->id ?? Service::factory(),
        ]);
    }

    /**
     * Associate the callback with a prayer request.
     */
    public function withPrayerRequest(?PrayerRequest $prayerRequest = null): static
    {
        return $this->state(fn (array $attributes) => [
            'prayer_request_id' => $prayerRequest?->id ?? PrayerRequest::factory(),
        ]);
    }
}

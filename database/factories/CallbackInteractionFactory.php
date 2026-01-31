<?php

namespace Database\Factories;

use App\Enums\CallbackStatus;
use App\Models\Callback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CallbackInteraction>
 */
class CallbackInteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'callback_id' => Callback::factory(),
            'notes' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(CallbackStatus::cases()),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Set the interaction status to Called.
     */
    public function called(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CallbackStatus::Called,
        ]);
    }

    /**
     * Set the interaction status to NoAnswer.
     */
    public function noAnswer(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CallbackStatus::NoAnswer,
        ]);
    }

    /**
     * Set the interaction status to FollowUp.
     */
    public function followUp(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CallbackStatus::FollowUp,
        ]);
    }

    /**
     * Set the interaction status to Completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CallbackStatus::Completed,
        ]);
    }

    /**
     * Set the interaction status to Closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CallbackStatus::Closed,
        ]);
    }
}

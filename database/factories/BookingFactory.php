<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'client_name' => fake()->name(),
            'client_email' => fake()->unique()->safeEmail(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'meeting_link' => fake()->url(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'duration' => fake()->randomElement([30, 45, 60]),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'confirmed' => false,
            'canceled' => false,
            'complete' => false,
        ];
    }
}

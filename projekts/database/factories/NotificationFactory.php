<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['new_reply', 'thread_mention', 'admin_alert']),
            'data' => [
                'thread_id' => $this->faker->randomNumber(),
                'message' => $this->faker->sentence(),
            ],
            'read_at' => null, // Notifications are unread by default
        ];
    }
}
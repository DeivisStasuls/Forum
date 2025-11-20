<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    $title = $this->faker->sentence();

    return [
        'user_id' => User::factory(),
        'category_id' => Category::factory(),
        'title' => $title,
        'slug' => Str::slug($title),
    ];
}

}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreadFactory extends Factory
{
    protected $model = Thread::class;

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

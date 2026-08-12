<?php

namespace Database\Factories;

use App\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class HajjUmrahFactory extends Factory
{
    protected $model = \App\HajjUmrah::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'image' => 'https://picsum.photos/seed/' . fake()->uuid() . '/800/500',
            'category_id' => Category::factory(),
            'type' => fake()->randomElement(['hajj', 'umrah']),
            'price' => fake()->randomFloat(2, 500, 10000),
            'duration_days' => fake()->numberBetween(5, 30),
            'published_at' => now(),
        ];
    }
}

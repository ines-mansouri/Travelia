<?php

namespace Database\Factories;

use App\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'location' => fake()->country(),
            'text' => fake()->paragraph(3),
            'image' => null,
            'rating' => fake()->numberBetween(4, 5),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Mitchell',
                'location' => 'United Kingdom',
                'text' => 'Travelia made our honeymoon absolutely magical! From the moment we booked, every detail was handled with care.',
                'image' => 'images/place-1.jpg',
                'rating' => 5,
                'sort_order' => 0,
            ],
            [
                'name' => 'James Kowalski',
                'location' => 'United States',
                'text' => 'I\'ve traveled with many agencies, but Travelia stands out. Their attention to detail is unmatched.',
                'image' => 'images/place-2.jpg',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Emma Schmidt',
                'location' => 'Germany',
                'text' => 'The Bali trip organized by Travelia was the perfect blend of adventure and relaxation.',
                'image' => 'images/place-3.jpg',
                'rating' => 5,
                'sort_order' => 2,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial
            );
        }
    }
}

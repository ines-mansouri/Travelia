<?php

namespace Database\Seeders;

use App\Hotel;
use App\HajjUmrah;
use App\Category;
use Illuminate\Database\Seeder;

class PilgrimageSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have Mecca and Medina hotels to link
        $meccaHotel = Hotel::firstOrCreate(
            ['name' => 'Clock Tower Hotel Mecca'],
            [
                'city' => 'Mecca',
                'country' => 'Saudi Arabia',
                'latitude' => 21.4189,
                'longitude' => 39.8262,
                'stars' => 5,
                'price_per_night_usd' => 250.00,
                'images' => ['/images/mecca-hotel.jpg'],
                'amenities' => ['wifi', 'restaurant', 'room_service', 'shuttle_to_haram'],
                'is_available' => true,
            ]
        );

        $medinaHotel = Hotel::firstOrCreate(
            ['name' => 'Al Eiman Royal Hotel Medina'],
            [
                'city' => 'Medina',
                'country' => 'Saudi Arabia',
                'latitude' => 24.4712,
                'longitude' => 39.6111,
                'stars' => 5,
                'price_per_night_usd' => 180.00,
                'images' => ['/images/medina-hotel.jpg'],
                'amenities' => ['wifi', 'restaurant', 'laundry'],
                'is_available' => true,
            ]
        );

        // Ensure category exists
        $category = Category::firstOrCreate(
            ['name' => 'Pilgrimage']
        );

        // Seed Hajj package
        HajjUmrah::firstOrCreate(
            ['title' => 'VIP 14-Day Hajj Package'],
            [
                'description' => 'A luxury Hajj travel package with five-star hotel mappings in Mecca and Medina.',
                'content' => 'Full-service guided pilgrimage tour including direct flights, luxury VIP tents in Arafat, and 5-star accommodation details.',
                'image' => '/images/hajj-promo.jpg',
                'category_id' => $category->id,
                'type' => 'hajj',
                'price' => 7500.00,
                'duration_days' => 14,
                'mecca_hotel_id' => $meccaHotel->id,
                'medina_hotel_id' => $medinaHotel->id,
                'published_at' => now(),
            ]
        );

        // Seed Umrah package
        HajjUmrah::firstOrCreate(
            ['title' => 'Economy 10-Day Umrah Package'],
            [
                'description' => 'A budget-friendly Umrah package mapped with close hotels in Mecca and Medina.',
                'content' => 'Complete package including visas, accommodation, transport, and tour guides.',
                'image' => '/images/umrah-promo.jpg',
                'category_id' => $category->id,
                'type' => 'umrah',
                'price' => 1800.00,
                'duration_days' => 10,
                'mecca_hotel_id' => $meccaHotel->id,
                'medina_hotel_id' => $medinaHotel->id,
                'published_at' => now(),
            ]
        );
    }
}

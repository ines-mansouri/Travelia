<?php

namespace Database\Factories;

use App\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    private const TUNISIAN_CITIES = [
        'Tunis', 'Sousse', 'Hammamet', 'Port El Kantaoui', 'Djerba',
        'Monastir', 'Sfax', 'Nabeul', 'Tabarka', 'Mahdia',
    ];

    private const CITY_COORDS = [
        'Tunis'             => [36.8065, 10.1815],
        'Sousse'            => [35.8256, 10.6369],
        'Hammamet'          => [36.4000, 10.6167],
        'Port El Kantaoui'  => [35.8925, 10.5942],
        'Djerba'            => [33.8076, 10.8452],
        'Monastir'          => [35.7643, 10.8113],
        'Sfax'              => [34.7400, 10.7600],
        'Nabeul'            => [36.4550, 10.7350],
        'Tabarka'           => [36.9544, 8.7580],
        'Mahdia'            => [35.5047, 11.0622],
    ];

    private const AMENITIES_POOL = [
        'Wi-Fi', 'Pool', 'Spa', 'Restaurant', 'Gym', 'Parking',
        'Airport Shuttle', 'Room Service', 'Beach Access', 'Bar',
        'Breakfast Included', 'Air Conditioning', 'Sea View',
        'Kids Club', 'Business Center',
    ];

    private const HOTEL_NAME_PREFIXES = [
        'Royal', 'Grand', 'La Maison', 'Hotel', 'Resort', 'Palace', 'Villa', 'Club',
    ];

    private const HOTEL_NAME_SUFFIXES = [
        'Beach', 'Luxury', 'Spa & Resort', 'Inn', 'Suites', 'Palace', 'Garden', 'Bay',
    ];

    public function definition(): array
    {
        $city = Arr::random(self::TUNISIAN_CITIES);
        $coords = self::CITY_COORDS[$city];
        $numImages = random_int(3, 6);
        $images = [];
        for ($i = 0; $i < $numImages; $i++) {
            $images[] = "https://picsum.photos/seed/hotel_" . uniqid() . "/800/500";
        }
        $amenityCount = random_int(3, 8);
        $amenities = collect(self::AMENITIES_POOL)->random($amenityCount)->values()->toArray();

        return [
            'name'               => Arr::random(self::HOTEL_NAME_PREFIXES) . ' ' . $this->faker->unique()->city() . ' ' . Arr::random(self::HOTEL_NAME_SUFFIXES),
            'city'               => $city,
            'country'            => 'TN',
            'latitude'           => $coords[0] + $this->faker->randomFloat(5, -0.05, 0.05),
            'longitude'          => $coords[1] + $this->faker->randomFloat(5, -0.05, 0.05),
            'stars'              => random_int(2, 5),
            'price_per_night_usd' => $this->faker->randomFloat(2, 35, 450),
            'images'             => $images,
            'amenities'          => $amenities,
            'is_available'       => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn () => ['is_available' => false]);
    }

    public function inCity(string $city): static
    {
        $coords = self::CITY_COORDS[$city] ?? [36.8065, 10.1815];
        return $this->state(fn () => [
            'city'      => $city,
            'latitude'  => $coords[0] + $this->faker->randomFloat(5, -0.03, 0.03),
            'longitude' => $coords[1] + $this->faker->randomFloat(5, -0.03, 0.03),
        ]);
    }

    public function stars(int $stars): static
    {
        return $this->state(fn () => ['stars' => $stars]);
    }
}

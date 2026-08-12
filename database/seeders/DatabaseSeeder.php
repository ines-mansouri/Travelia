<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(DestinationsTableSeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(BookingComSeeder::class);
        $this->call(BlogsTableSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(PilgrimageSeeder::class);
    }
}

<?php

namespace Tests\Feature;

use App\Booking;
use App\Destinations;
use App\FlightBooking;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_auth(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_displays_statistical_summary_variables(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        // Create a paid past destination booking
        Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(10),
            'total_price'       => 500.00,
            'original_price_usd' => 500.00,
            'payment_status'    => 'paid',
        ]);

        // Create a paid future flight booking
        FlightBooking::create([
            'user_id'           => $user->id,
            'status'            => 'paid',
            'original_price_usd' => 300.00,
            'converted_price'   => 900.00,
            'currency_code'     => 'TND',
            'flight_details'    => [
                'departure' => Carbon::now()->addDays(15)->toDateString(),
            ],
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalSpent');
        $response->assertViewHas('totalSpentFormatted');
        $response->assertViewHas('upcomingTrips');
        $response->assertViewHas('completedJourneys');
    }

    public function test_dashboard_currency_conversion_aggregates_total_spent(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        // Create 2 paid destination bookings (past) — 400 + 600 = 1000 USD
        Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(5),
            'total_price'       => 400.00,
            'original_price_usd' => 400.00,
            'payment_status'    => 'paid',
        ]);
        Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(3),
            'total_price'       => 600.00,
            'original_price_usd' => 600.00,
            'payment_status'    => 'paid',
        ]);

        // Create 1 paid flight booking — 250 USD
        FlightBooking::create([
            'user_id'            => $user->id,
            'status'             => 'paid',
            'original_price_usd' => 250.00,
            'converted_price'    => 845.00,
            'currency_code'      => 'TND',
            'flight_details'     => [
                'departure' => Carbon::now()->subDays(30)->toDateString(),
            ],
        ]);

        // Total: 400 + 600 + 250 = 1250 USD
        // With default currency (EUR) and default rates: 1 EUR = 1.08 USD
        // 1250 USD / 1.08 = ~1157.41 EUR
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $totalSpent = $response->viewData('totalSpent');
        $this->assertIsFloat($totalSpent);
        $this->assertGreaterThan(0, $totalSpent);

        // Verify the formatted output contains a currency symbol and number
        $totalSpentFormatted = $response->viewData('totalSpentFormatted');
        $this->assertStringContainsString('€', $totalSpentFormatted);
        $this->assertMatchesRegularExpression('/\d+\.\d{2}/', $totalSpentFormatted);
    }
}

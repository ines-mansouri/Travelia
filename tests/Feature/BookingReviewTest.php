<?php

namespace Tests\Feature;

use App\Booking;
use App\BookingReview;
use App\Destinations;
use App\FlightBooking;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_review_own_past_completed_destination_booking(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $booking = Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(10),
            'payment_status'    => 'paid',
            'original_price_usd' => 500,
        ]);

        $response = $this->actingAs($user)->postJson(route('booking-reviews.store'), [
            'rating'       => 5,
            'comment'      => 'Amazing trip!',
            'booking_type' => 'destination',
            'booking_id'   => $booking->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('booking_reviews', [
            'user_id'         => $user->id,
            'reviewable_id'   => $booking->id,
            'reviewable_type' => Booking::class,
            'rating'          => 5,
            'comment'         => 'Amazing trip!',
        ]);
    }

    public function test_user_cannot_review_a_future_booking(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $booking = Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->addDays(30),
            'payment_status'    => 'paid',
            'original_price_usd' => 500,
        ]);

        $response = $this->actingAs($user)->postJson(route('booking-reviews.store'), [
            'rating'       => 4,
            'comment'      => 'Not yet traveled',
            'booking_type' => 'destination',
            'booking_id'   => $booking->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Only completed journeys (past travel date) can be reviewed.',
        ]);

        $this->assertDatabaseMissing('booking_reviews', [
            'user_id'       => $user->id,
            'reviewable_id' => $booking->id,
        ]);
    }

    public function test_user_cannot_review_a_booking_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $destination = Destinations::factory()->create();

        $booking = Booking::factory()->create([
            'user_id'           => $owner->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(5),
            'payment_status'    => 'paid',
            'original_price_usd' => 300,
        ]);

        $response = $this->actingAs($other)->postJson(route('booking-reviews.store'), [
            'rating'       => 3,
            'comment'      => 'Nice',
            'booking_type' => 'destination',
            'booking_id'   => $booking->id,
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'You do not own this booking.',
        ]);
    }

    public function test_endpoint_rejects_duplicate_review_for_same_booking(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $booking = Booking::factory()->create([
            'user_id'           => $user->id,
            'destination_id'    => $destination->id,
            'status'            => 'paid',
            'travel_date'       => Carbon::now()->subDays(7),
            'payment_status'    => 'paid',
            'original_price_usd' => 400,
        ]);

        // First review — should succeed
        $this->actingAs($user)->postJson(route('booking-reviews.store'), [
            'rating'       => 4,
            'comment'      => 'Great experience!',
            'booking_type' => 'destination',
            'booking_id'   => $booking->id,
        ])->assertJson(['success' => true]);

        // Second review — should be rejected as duplicate
        $response = $this->actingAs($user)->postJson(route('booking-reviews.store'), [
            'rating'       => 5,
            'comment'      => 'Even better than I thought!',
            'booking_type' => 'destination',
            'booking_id'   => $booking->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'You have already reviewed this booking.',
        ]);

        // Only one review should exist
        $this->assertEquals(1, BookingReview::where('user_id', $user->id)
            ->where('reviewable_id', $booking->id)
            ->where('reviewable_type', Booking::class)
            ->count());
    }
}

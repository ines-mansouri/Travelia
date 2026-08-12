<?php

namespace Tests\Feature;

use App\Destinations;
use App\Review;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_store_requires_auth(): void
    {
        $destination = Destinations::factory()->create();

        $response = $this->post(route('reviews.store', $destination), [
            'rating' => 5,
            'comment' => 'Great!',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $response = $this->actingAs($user)->post(route('reviews.store', $destination), [
            'rating' => 5,
            'comment' => 'Amazing destination!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'rating' => 5,
        ]);
    }

    public function test_review_requires_rating(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $response = $this->actingAs($user)->post(route('reviews.store', $destination), [
            'comment' => 'No rating',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_review_rating_must_be_between_1_and_5(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();

        $response = $this->actingAs($user)->post(route('reviews.store', $destination), [
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_user_can_update_existing_review(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();
        Review::create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'rating' => 3,
            'comment' => 'Okay',
        ]);

        $response = $this->actingAs($user)->post(route('reviews.store', $destination), [
            'rating' => 5,
            'comment' => 'Updated!',
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, Review::count());
        $this->assertEquals(5, Review::first()->rating);
    }

    public function test_user_can_delete_own_review(): void
    {
        $user = User::factory()->create();
        $destination = Destinations::factory()->create();
        $review = Review::create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($user)->delete(route('reviews.destroy', $review));

        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_user_cannot_delete_others_review(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $destination = Destinations::factory()->create();
        $review = Review::create([
            'user_id' => $user1->id,
            'destination_id' => $destination->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($user2)->delete(route('reviews.destroy', $review));

        $response->assertStatus(403);
    }
}

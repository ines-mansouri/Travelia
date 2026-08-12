<?php

namespace Tests\Feature;

use App\Category;
use App\Destinations;
use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_page_requires_auth(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_wishlist_page_can_be_rendered(): void
    {
        Destinations::factory()->create();
        Tag::factory()->create();
        Category::factory()->create();

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
    }

    public function test_can_add_to_wishlist(): void
    {
        $cat = Category::factory()->create();
        $destination = Destinations::factory()->create(['category_id' => $cat->id]);

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('wishlist.store', $destination));

        $response->assertRedirect();
        $this->assertTrue($user->hasWishlisted($destination));
    }

    public function test_can_remove_from_wishlist(): void
    {
        $cat = Category::factory()->create();
        $destination = Destinations::factory()->create(['category_id' => $cat->id]);

        $user = \App\User::factory()->create();
        $user->wishlist()->create(['destination_id' => $destination->id]);

        $response = $this->actingAs($user)->delete(route('wishlist.destroy', $destination));

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->hasWishlisted($destination));
    }

    public function test_toggle_wishlist_returns_json(): void
    {
        $cat = Category::factory()->create();
        $destination = Destinations::factory()->create(['category_id' => $cat->id]);

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('wishlist.toggle', $destination));

        $response->assertOk()
            ->assertJson(['wishlisted' => true]);
    }

    public function test_toggle_wishlist_removes_if_already_added(): void
    {
        $cat = Category::factory()->create();
        $destination = Destinations::factory()->create(['category_id' => $cat->id]);

        $user = \App\User::factory()->create();
        $user->wishlist()->create(['destination_id' => $destination->id]);

        $response = $this->actingAs($user)->postJson(route('wishlist.toggle', $destination));

        $response->assertOk()
            ->assertJson(['wishlisted' => false]);
    }
}

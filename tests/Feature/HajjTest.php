<?php

namespace Tests\Feature;

use App\Category;
use App\HajjUmrah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HajjTest extends TestCase
{
    use RefreshDatabase;

    public function test_hajj_single_returns_404_for_missing(): void
    {
        $response = $this->get('/hajj/99999');

        $response->assertStatus(404);
    }

    public function test_hajj_single_can_be_rendered(): void
    {
        $cat = Category::factory()->create();

        $hajjUmrah = HajjUmrah::factory()->create(['category_id' => $cat->id]);

        $response = $this->get(route('hajj.show', $hajjUmrah->id));

        $response->assertStatus(200);
        $response->assertSee($hajjUmrah->title);
    }

    public function test_hajj_with_search_filter(): void
    {
        $response = $this->get('/hajj?search=package&type=hajj&sort=title');

        $response->assertStatus(200);
    }
}

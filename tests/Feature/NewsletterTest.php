<?php

namespace Tests\Feature;

use App\Newsletter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_subscribe_stores_email(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('newsletters', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_newsletter_requires_valid_email(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_newsletter_prevents_duplicate_email(): void
    {
        Newsletter::create(['email' => 'test@example.com']);

        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }
}

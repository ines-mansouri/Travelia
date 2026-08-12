<?php

namespace Tests\Feature;

use App\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
    }

    public function test_contact_form_stores_submission(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_form_validates_email(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'email' => 'not-an-email',
            'subject' => 'Test',
            'message' => 'Test message.',
        ]);

        $response->assertSessionHasErrors('email');
    }
}

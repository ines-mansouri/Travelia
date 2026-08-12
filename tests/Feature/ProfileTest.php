<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_profile_text_fields(): void
    {
        $user = User::factory()->create([
            'name'  => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->putJson(route('profile.update'), [
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_authenticated_user_can_upload_valid_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->postJson(route('profile.avatar'), [
            'avatar' => $file,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        // Assert the file exists on the virtual disk
        $this->assertNotNull($user->fresh()->avatar_path);
        Storage::disk('public')->assertExists($user->fresh()->avatar_path);
    }

    public function test_avatar_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->postJson(route('profile.avatar'), [
            'avatar' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($user->fresh()->avatar_path);
    }

    public function test_avatar_upload_rejects_oversized_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('large.jpg', 3072, 'image/jpeg');

        $response = $this->actingAs($user)->postJson(route('profile.avatar'), [
            'avatar' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($user->fresh()->avatar_path);
    }
}

<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('avatars');
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_can_create_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/post', [
            'content' => 'Test post content',
            'tags' => ['test', 'api']
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['message', 'post']);
    }

    public function test_user_can_like_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = \App\Models\Post::factory()->create();

        $response = $this->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Post liked successfully']);
    }

    public function test_user_can_comment_on_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = \App\Models\Post::factory()->create();

        $response = $this->postJson('/api/comments', [
            'post_id' => $post->id,
            'content' => 'Test comment'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['message', 'comment']);
    }

    public function test_user_can_follow_another_user()
    {
        $user = User::factory()->create();
        $userToFollow = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/follow', [
            'user_id' => $userToFollow->id
        ]);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Follow request sent successfully']);
    }

    public function test_user_can_search_news_feed()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/news-feed/search?query=test');

        $response->assertStatus(200)
                ->assertJsonStructure(['data']);
    }

    public function test_user_can_upload_avatar()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/upload-avatar', [
            'avatar' => $file
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['message', 'avatar_url']);
    }

    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'email',
                    'avatar_url'
                ]);
    }

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile', [
            'name' => 'Updated Name',
            'bio' => 'Updated bio'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['message', 'user']);
    }
}

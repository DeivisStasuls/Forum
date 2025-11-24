<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    // --- Access & Creation Tests ---

    #[Test]
    public function guests_can_view_all_threads(): void
    {
        Thread::factory()->count(3)->create();
        $this->getJson('/api/threads')->assertOk()->assertJsonCount(3, 'data');
    }

    #[Test]
    public function authenticated_user_can_create_thread(): void
    {
        $threadData = [
            'title' => 'My New Forum Thread',
            'body' => 'Checking if I can successfully create a new thread.',
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->user, 'sanctum')
             ->postJson('/api/threads', $threadData)
             ->assertStatus(201)
             ->assertJsonPath('thread.title', 'My New Forum Thread');

        $this->assertDatabaseHas('threads', ['user_id' => $this->user->id]);
    }

    #[Test]
    public function thread_creation_requires_valid_fields(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/threads', [
                             'title' => '',
                             'body' => 'Valid body.',
                             'category_id' => 999, // Invalid Category ID
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'category_id']);
    }

    // --- Authorization Tests (Crucial Security Check) ---

    #[Test]
    public function only_thread_owner_can_update_thread(): void
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();

        // 1. Owner can update
        $this->actingAs($this->user, 'sanctum')
             ->putJson("/api/threads/{$thread->id}", ['title' => 'Updated Title'])
             ->assertOk();
        $this->assertEquals('Updated Title', $thread->fresh()->title);

        // 2. Other user cannot update
        $this->actingAs($otherUser, 'sanctum')
             ->putJson("/api/threads/{$thread->id}", ['title' => 'Attempted Bad Update'])
             ->assertStatus(403);
        $this->assertNotEquals('Attempted Bad Update', $thread->fresh()->title);
    }

    #[Test]
    public function only_thread_owner_can_delete_thread(): void
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();

        // 1. Other user cannot delete
        $this->actingAs($otherUser, 'sanctum')
             ->deleteJson("/api/threads/{$thread->id}")
             ->assertStatus(403);

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        // 2. Owner can delete
        $this->actingAs($this->user, 'sanctum')
             ->deleteJson("/api/threads/{$thread->id}")
             ->assertStatus(200);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }
}
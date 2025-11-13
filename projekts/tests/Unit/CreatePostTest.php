<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Thread;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_post_to_thread()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/threads/{$thread->id}/posts", [
            'body' => 'This is a test post.'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', ['body' => 'This is a test post.']);
    }
}

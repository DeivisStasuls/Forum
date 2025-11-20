<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_post()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'body' => 'This is a post to delete.',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'This is a post to delete.',
        ]);

        // Dzēš postu
        $post->delete();

        // Pārbauda, ka tas vairs nav datubāzē
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}

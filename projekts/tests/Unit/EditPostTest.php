<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_update_a_post_body()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'body' => 'Original body',
        ]);

        $this->assertEquals('Original body', $post->body);

        // Atjaunina postu
        $post->update([
            'body' => 'Updated body',
        ]);

        // Atsvaidzina model objektu no datubāzes
        $post->refresh();

        $this->assertEquals('Updated body', $post->body);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'Updated body',
        ]);
    }
}

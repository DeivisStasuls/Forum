<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_post()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);

        $postData = [
            'body' => 'This is a test post.',
            'user_id' => $user->id,
            'thread_id' => $thread->id,
        ];

        $post = Post::create($postData);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'This is a test post.',
            'user_id' => $user->id,
            'thread_id' => $thread->id,
        ]);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('This is a test post.', $post->body);
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals($thread->id, $post->thread_id);
    }
}

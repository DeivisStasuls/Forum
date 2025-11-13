<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Post;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->patch("/posts/{$post->id}", [
            'body' => 'Updated post content'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', ['body' => 'Updated post content']);
    }
}

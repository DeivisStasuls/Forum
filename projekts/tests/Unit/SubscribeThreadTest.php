<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Thread;

class SubscribeThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_subscribe_to_thread()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/threads/{$thread->id}/subscribe");

        $response->assertStatus(200);
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'thread_id' => $thread->id
        ]);
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeThreadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_subscribe_to_a_thread()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create();

        // Lietotājs pierakstās
        $user->subscriptions()->attach($thread->id);

        // Pārbauda, ka datubāzē ir ieraksts
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'thread_id' => $thread->id,
        ]);

        // Pārbauda attiecību
        $this->assertTrue($user->subscriptions->contains($thread));
        $this->assertTrue($thread->subscribers->contains($user));
    }

    #[Test]
    public function user_can_unsubscribe_from_a_thread()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create();

        // Pierakstās un pēc tam atceļ
        $user->subscriptions()->attach($thread->id);
        $user->subscriptions()->detach($thread->id);

        $this->assertDatabaseMissing('subscriptions', [
            'user_id' => $user->id,
            'thread_id' => $thread->id,
        ]);

        $user->refresh();
        $thread->refresh();

        $this->assertFalse($user->subscriptions->contains($thread));
        $this->assertFalse($thread->subscribers->contains($user));
    }
}

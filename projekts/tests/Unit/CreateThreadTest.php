<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_thread()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/threads', [
            'title' => 'New Thread',
            'body' => 'This is the body of the thread.'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('threads', ['title' => 'New Thread']);
    }

    /** @test */
    public function guest_cannot_create_thread()
    {
        $response = $this->post('/threads', [
            'title' => 'Unauthorized Thread',
            'body' => 'No permission'
        ]);

        $response->assertStatus(302); // redirects to login
    }
}

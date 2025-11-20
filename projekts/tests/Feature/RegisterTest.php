<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123', // ja izmanto confirmation
        ];

        $response = $this->postJson('/register', $data);

        $response->assertStatus(201) // vai 200 atkarībā no tavas API
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'token', // ja API autentifikācija ar token
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $data = [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/register', $data);

        $response->assertStatus(422); // Validation error
        $this->assertCount(1, User::where('email', 'test@example.com')->get());
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        // Izveidojam lietotāju
        $user = User::factory()->create([
            'password' => bcrypt($password = 'secret123'),
        ]);

        // Mēģina pieteikties
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token', // ja izmanto API token
                     'user' => ['id', 'name', 'email']
                 ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422); // vai 401 atkarībā no tavas API validācijas
        $this->assertGuest();
    }
}

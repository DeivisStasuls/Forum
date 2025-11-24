<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordHashTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function password_is_hashed_when_user_is_created()
    {
        $password = 'secret123';

        $user = User::factory()->create([
            'password' => $password,
        ]);

        // Parbauda, ka parole nav saglabāta kā plaintext
        $this->assertNotEquals($password, $user->password);

        // Parbauda, ka parole sakrīt ar Hash::check
        $this->assertTrue(Hash::check($password, $user->password));
    }
}

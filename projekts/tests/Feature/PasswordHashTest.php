<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordHashTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function password_is_hashed_when_user_is_created()
    {
        $user = User::factory()->create([
            'password' => 'plainpassword'
        ]);

        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }
}

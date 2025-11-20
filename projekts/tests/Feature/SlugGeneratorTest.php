<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class SlugGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function slug_is_generated_from_title_if_not_provided()
    {
        $user = User::factory()->create();

        $thread = Thread::factory()->create([
            'title' => 'My Test Thread',
            'slug' => null,
            'user_id' => $user->id,
        ]);

        $this->assertEquals(Str::slug('My Test Thread'), $thread->slug);
    }

    /** @test */
    public function slug_remains_the_same_if_provided()
    {
        $user = User::factory()->create();

        $thread = Thread::factory()->create([
            'title' => 'Another Thread',
            'slug' => 'custom-slug',
            'user_id' => $user->id,
        ]);

        $this->assertEquals('custom-slug', $thread->slug);
    }
}

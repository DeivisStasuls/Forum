<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Thread;

class SlugGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function thread_slug_is_generated_correctly()
    {
        $thread = Thread::factory()->create([
            'title' => 'This is a Test Thread'
        ]);

        $this->assertEquals('this-is-a-test-thread', $thread->slug);
    }
}

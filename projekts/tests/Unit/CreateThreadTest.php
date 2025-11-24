<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_thread()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $threadData = [
            'title' => 'My Test Thread',
            'body' => 'This is the body of the test thread.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ];

        $thread = Thread::create($threadData);

        $this->assertDatabaseHas('threads', [
            'id' => $thread->id,
            'title' => 'My Test Thread',
            'body' => 'This is the body of the test thread.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Thread::class, $thread);
        $this->assertEquals('My Test Thread', $thread->title);
        $this->assertEquals($user->id, $thread->user_id);
        $this->assertEquals($category->id, $thread->category_id);
    }

    #[Test]
    public function a_thread_belongs_to_a_user_and_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $thread = Thread::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertEquals($user->id, $thread->user->id);
        $this->assertEquals($category->id, $thread->category->id);
    }
}

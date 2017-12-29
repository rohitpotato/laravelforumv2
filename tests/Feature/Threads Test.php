<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        $this->thread->factory('App\Thread')->create();
    }

    public function a_user_Can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertSee($this->thread->title);
    }

    public function a_user_can_read_replies_associated_with_that_thread()
    {
        factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/'.$this->thread->id);
        $response = assertSee($reply->body);

    }

    public function a_thread_has_creator()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceof('App\User', $thread->user);
    }

    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply(['body' => 'Foobar', 'user_id' =>1 ]);

        $thread->assertCount(1, $thread->replies);
    }
}

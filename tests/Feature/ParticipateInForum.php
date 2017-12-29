<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForum extends TestCase
{
	use DatabaseMigrations;
	
	function an_authenticated_user_may_participate_in_forum_threads()
	{
		$user = factory('App\User')->create();
		
		$this->be($user = factory('App\User')->create());

		$thread = factory('App\Thread')->create();

		$reply = factory('App\Reply')->create();
		
		$this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

		$this->get($thread->path())->assertSee($reply->body);
	}
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
	use DatabaseMigrations;

 	function an_authenticated_user_can_create_threads()
 	{
 		$this->actingAs(factory('App\User')->create());

 		$thread = factory('App\Thread')->raw();

 		$this->post('/threads'.$thread->toArray()); 

 		$this->get($thread->path());

 		$this->assertSee($thread->title);
 	}  
}

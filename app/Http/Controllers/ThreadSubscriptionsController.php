<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Channel;
class ThreadSubscriptionsController extends Controller
{
    public function store(Channel $channel, Thread $thread)
    {
    	$thread->subscribe(auth()->id());
    }

   public function destroy(Channel $channel, Thread $thread)
   {
   		$thread->unsubscribe(auth()->id());
   }
}

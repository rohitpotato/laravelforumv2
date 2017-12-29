<?php

namespace App\Listener;

use App\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadRecievedNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {
         User::whereIn('name', $event->reply->mentionedUsers())
         ->get()
            ->each(function ($user) use ($event) {

                $user->notify(new YouWereMentioned($event->reply));
            });

    }
}

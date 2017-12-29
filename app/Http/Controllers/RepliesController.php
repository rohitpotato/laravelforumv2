<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Gate;
use App\Inspections\Spam;
use App\Http\Forms\CreatePostForm;
use App\User;
use App\Notifications\YouWereMentioned;
class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }


    public function store($channelId, Thread $thread, CreatePostForm $form)
    {

            $reply =  $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
                ]);

            
            return $reply->load('owner');
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply, Spam $spam)
    {

           $this->authorize('update', $reply);

           $this->validate(request(), ['body' => 'required|spamfree']);

          // $spam->detect(request('body'));

           $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}

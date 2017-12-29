<?php

namespace App;

use App\Events\NotifySubscribers;
use App\Events\ThreadRecievedNewReply;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;


class Thread extends Model
{
    use RecordsActivity, RecordsVisits;

    protected $guarded = [];

 
    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

  
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

   
    public function path()
    {
        return "/forums/public/threads/{$this->channel->slug}/{$this->slug}";
    }

 
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    } 

   
    public function addReply($reply)
    {
       $reply = $this->replies()->create($reply);

       event(new ThreadRecievedNewReply($reply));
    
        $this->notifySubscribers($reply);
        
         return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions->where('user_id', '!=', $reply->user_id)->each->notify($reply);
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create(['user_id' => auth()->id() ]);

        return $this;
    }

    public function unsubscribe()
    {
        $this->subscriptions()->where('user_id', auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);

        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return Redis::get("threads.{$this->id}.visits");
    }

    public function recordVisits()
    {
        Redis::incr("threads.{$this->id}.visits");

        return $this;
    }

    public function getRouteKeyName() 
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
       if (static::whereSlug($slug = str_slug($value))->exists()) {
           $slug = $this->incrementSlug($slug);
       }

       $this->attributes['slug'] = $slug;
   }

     /**
      * Increment a slug's suffix.
      *
      * @param  string $slug
      * @return string
      */
     protected function incrementSlug($slug)
     {
       $max = static::whereTitle($this->title)->latest('id')->value('slug');

       if (is_numeric(substr($max, -1, 1))) {
           return preg_replace_callback('/(\d)$/', function ($matches) {
               return $matches[1] + 1;
           }, $max);
       }

       return "{$slug}-2";
   }

   public function markBestReply($reply)
   {
      $this->update(['best_reply_id' => $reply->id]);
   }

}

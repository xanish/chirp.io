<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id', 'tweet_id',
    ];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liked($user_id, $tweet_id)
    {
        return $this->where(['user_id' => $user_id, 'tweet_id' => $tweet_id])->count();
    }

    public function like($user_id, $tweet_id)
    {
        return $this->create([
            'user_id' => $user_id,
            'tweet_id' => $tweet_id,
        ]);
    }

    public function unlike($user_id, $tweet_id)
    {
        $likes = $this->where([
            'user_id' => $user_id,
            'tweet_id' => $tweet_id,
        ]);
        return $likes->delete();
    }

    public function tweets($tweet_ids)
    {
        return $this->whereIn('tweet_id', $tweet_ids)->select('tweet_id', 'user_id')->get();
    }
}

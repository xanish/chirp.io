<?php
namespace App\ServiceObjects;

use Auth;
use App\Like;

class LikeServiceObject
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function like($tweet_id)
    {
        $id = Auth::id();
        $check = $this->like->where(['user_id' => $id, 'tweet_id' => $tweet_id])->count();
        if ($check != 0) {
            return;
        }
        $like = $this->like->create([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
    }

    public function unlike($tweet_id)
    {
        $id = Auth::id();
        $like = $this->like->where([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
        $like->delete();
    }
}

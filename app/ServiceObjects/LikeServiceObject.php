<?php
namespace App\ServiceObjects;

use Auth;
use App\Like;

class LikeServiceObject {

    public function like($tweet_id)
    {
        $id = Auth::id();
        $check = Like::where(['user_id' => $id, 'tweet_id' => $tweet_id])->count();
        if ($check != 0) {
            return;
        }
        $like = Like::create([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
    }

    public function unlike($tweet_id)
    {
        $id = Auth::id();
        $like = Like::where([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
        $like->delete();
    }
}

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
        $id = Auth::user()->id;
        $check = $this->like->where(['user_id' => $id, 'tweet_id' => $tweet_id])->count();
        if ($check == 0) {
            $like = $this->like->create([
                'user_id' => $id,
                'tweet_id' => $tweet_id,
            ]);
        }
        return response()->json(200);
    }

    public function unlike($tweet_id)
    {
        $id = Auth::user()->id;
        $check = $this->like->where(['user_id' => $id, 'tweet_id' => $tweet_id])->count();
        if ($check > 0) {
            $like = $this->like->where([
                'user_id' => $id,
                'tweet_id' => $tweet_id,
            ]);
            $like->delete();
        }
        return response()->json(200);
    }
}

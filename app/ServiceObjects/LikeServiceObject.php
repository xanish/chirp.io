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
        try {
            $check = $this->like->liked($id, $tweet_id);
            if ($check == 0) {
                $like = $this->like->like($id, $tweet_id);
            }
        } catch (Exception $e) {
            throw new Exception("Unable To Like Post");
        }
        return response()->json(200);
    }

    public function unlike($tweet_id)
    {
        $id = Auth::user()->id;
        try {
            $check = $this->like->liked($id, $tweet_id);
            if ($check > 0) {
                $like = $this->like->unlike($id, $tweet_id);
            }
        } catch (Exception $e) {
            throw new Exception("Unable To Unlike Post");
        }
        return response()->json(200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Like;
use App\Tweet;
use App\User;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function like($tweet_id)
    {
        $id = Auth::id();
        $like = Like::create([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
        return redirect()->back();
    }

    public function unlike($tweet_id)
    {
        $id = Auth::id();
        $like = Like::where([
            'user_id' => $id,
            'tweet_id' => $tweet_id,
        ]);
        $like->delete();
        return redirect()->back();
    }
}

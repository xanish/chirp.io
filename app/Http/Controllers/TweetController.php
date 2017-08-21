<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\Utils\Utils;
use Auth;

class TweetController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $tweet = new Tweet;
        $text = $request->tweet_text;
        $image_name = null;
        if ($request->tweet_image){
            $image_name = (new Utils)->fitAndSaveImage($user->id, $request->tweet_image, 600, 600, 'tweet_images', 'scale-down');
        }
        $tweet->createTweet($user->id, $text, $image_name);
        return response()->json(200);
    }
}

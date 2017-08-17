<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
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
            $image_name = $user->id.'_'.time().'.'.$request->tweet_image->getClientOriginalExtension();
            $request->tweet_image->move(public_path('tweet_images'), $image_name);
            //   if (in_array($request->tweet_image->getClientOriginalExtension(), array('.jpeg','.png','.jpg'))) {
            //       $image_name = $user->id.'_'.time().'.'.$request->tweet_image->getClientOriginalExtension();
            //       Image::make(Input::file('tweet_image'))->save('tweet_images/'.$image_name);
            //   }
        }
        $tweet->createTweet($user->id, $text, $image_name);
        return response()->json(200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use Auth;

class TweetController extends Controller
{
  public function create(Request $request)
  {
        $tweet = new Tweet;
        $text = $request->tweet_text;
        $tweet->createTweet($text);
        return redirect('/'.Auth::user()->username);
  }
}

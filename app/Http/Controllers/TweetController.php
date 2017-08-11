<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TweetController extends Controller
{
  public function create(Request $request)
  {
        $tweet = new Tweet;
        $tweet->text = $request->text;
        $tweet->createTweet($request);
  }
}

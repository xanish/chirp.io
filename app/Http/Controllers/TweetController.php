<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;

class TweetController extends Controller
{
  public function create(Request $request)
  {
        $tweet = new Tweet;
        $text = $request->input('tweet');
        $tweet->createTweet($text);
        return response()->json(200);
  }
}

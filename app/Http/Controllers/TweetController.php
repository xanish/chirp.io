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
      $text = $request->tweet;
      $tweet->createTweet($user->id, $text);
      return response()->json(200);
  }
}

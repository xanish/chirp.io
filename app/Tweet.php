<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use DB;

class Tweet extends Model
{
    public function createTweet($tweet)
    {
      $userid = Auth::user()->id;
      DB::table('tweets')->insert(
          ['text' => $tweet->text, 'user_id' => $userid, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
      );
    }

    public function getTweets()
    {
      $userid = Auth::user()->id;
      $tweets = DB::table('tweets')->select('text','created_at')
                                   ->where('user_id', $userid)
                                   ->orderBy('created_at', 'desc')
                                   ->get();
      return $tweets;
    }
}

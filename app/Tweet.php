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
      if($tweet != NULL)
      {
        $id = DB::table('tweets')->insertGetId(
            ['text' => $tweet, 'user_id' => $userid, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
          );

        return $id; 
      }
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

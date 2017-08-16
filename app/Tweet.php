<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use DB;
use App\User;

class Tweet extends Model
{
    public function createTweet($tweet)
    {
      $userid = Auth::user()->id;
      Tweet::insert([
          'text' => $tweet,
          'user_id' => $userid,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
      ]);
    }

    public function getTweets($username)
    {
      $userid = (new User)->getUserId($username);
      $tweets = Tweet::where('user_id', $userid)
        ->orderBy('created_at', 'desc')
        ->get();
      return $tweets;
    }
}

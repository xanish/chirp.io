<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Tweet extends Model
{
    protected $fillable = [
        'text', 'user_id',
    ];

    public function createTweet($tweet)
    {
      Tweet::create([
          'text' => $tweet,
          'user_id' => $userid,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
      ]);
    }

    public function getTweets($userid)
    {
      $tweets = Tweet::where('user_id', $userid)
        ->orderBy('id', 'desc')
        ->paginate(20);
      return $tweets;
    }

    public function getTweetCountForPerson($userid)
    {
        return Tweet::where('user_id', $userid)->count();
    }

    public function getTweetsForMultipleIds($ids)
    {
        $tweets = Tweet::whereIn('user_id', $ids)
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->latest()
                    ->select('users.name', 'users.username', 'users.profile_image', 'tweets.text', 'tweets.created_at')
                    ->get();
        return $tweets;
    }
}

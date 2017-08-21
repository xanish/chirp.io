<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tweet extends Model
{
    protected $fillable = [
        'text', 'user_id',
    ];

    public function createTweet($userid, $tweet, $image_name)
    {
      Tweet::insert([
          'text' => $tweet,
          'user_id' => $userid,
          'tweet_image' => $image_name,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
      ]);
    }

    public function getTweets($userid)
    {
      $tweets = Tweet::where('user_id', $userid)
        ->orderBy('created_at', 'desc')
        ->get();
      return $tweets;
    }

    public function getTweetsCount($userid)
    {
        return Tweet::where('user_id', $userid)->count();
    }

    public function getTweetsFromPeople($followingids, $unfollowedids)
    {
        $tweets = Tweet::whereIn('user_id', $followingids)
                    ->orWhereIn('user_id', $unfollowedids)
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->select('users.name', 'users.username', 'users.profile_image', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at')
                    ->latest()
                    ->paginate(20);
        return $tweets;
    }
}

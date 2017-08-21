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
        ->orderBy('id', 'desc')
        ->paginate(20);
      return $tweets;
    }

    public function getTweetCountForPerson($userid)
    {
        return Tweet::where('user_id', $userid)->count();
    }

    public function getTweetsForMultipleIds($followingids, $unfollowedids)
    {
        $query1 = Tweet::whereIn('user_id', $followingids)
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->select('users.name', 'users.username', 'users.profile_image', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at');
        $query2 = Tweet::whereIn('user_id', $unfollowedids)
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->select('users.name', 'users.username', 'users.profile_image', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at')
                    ->latest();
        $tweets = $query1->union($query2)->latest()->get();
        return $tweets;
    }

    public function getTweetCountForMultipleIds($ids)
    {
        return Tweet::whereIn('user_id', $ids)
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->count();
    }
}

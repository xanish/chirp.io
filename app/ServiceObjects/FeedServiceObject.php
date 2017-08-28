<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Tweet;

class FeedServiceObject {

    public function getFeed()
    {
        $id = Auth::id();
        $user = User::find($id);
        $tweet_count = $user->tweets()->count();
        $follower_count = $user->followers()->count();
        $following_count = $user->following()->count();
        $followingids = $user->following()->pluck('follows');

        $feed = Tweet::whereIn('user_id', $followingids)
        ->join('users', 'tweets.user_id', '=', 'users.id')
        ->select('users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at')
        ->latest()->get();

        $posts = $this->parseFeed($feed);

        return array(
            'user' => $user,
            'posts' => $posts,
            'tweet_count' => $tweet_count,
            'follower_count' => $follower_count,
            'following_count' => $following_count,
        );
    }

    public function parseFeed($feed)
    {
        $posts = [];
        foreach ($feed as $tweet) {
          $post = array(
              'id' => $tweet->id,
              'text' => $tweet->text,
              'tweet_image' => $tweet->tweet_image,
              'created_at' => $tweet->created_at,
              'likes' => $tweet->likes()->count(),
              'name' => $tweet->name,
              'username' => $tweet->username,
              'profile_image' => $tweet->profile_image,
          );
          array_push($posts, (object)$post);
        }
        return $posts;
    }
}

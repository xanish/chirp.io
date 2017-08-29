<?php
namespace App\ServiceObjects;

use App\User;
use Auth;
use App\Utils\Utils;

class UserProfileServiceObject
{
    private $user;
    private $utils;

    public function __construct(User $user, Utils $utils)
    {
        $this->user = $user;
        $this->utils = $utils;
    }

    private function getBaseDetails($username)
    {
        $user = $this->user->where('username', $username)->firstOrFail();
        return array(
            'user' => $user,
            'tweet_count' => $user->tweets()->count(),
            'follower_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        );
    }

    public function getProfile($username)
    {
        $baseData = $this->getBaseDetails($username);
        $tweets = $baseData['user']->tweets()->get();
        $posts = [];
        $liked = Auth::user()->likes()->pluck('tweet_id')->toArray();
        foreach ($tweets as $tweet) {
            $post = array(
                'id' => $tweet->id,
                'text' => $tweet->text,
                'tweet_image' => $tweet->tweet_image,
                'created_at' => $tweet->created_at,
                'likes' => $tweet->likes()->count(),
            );
            array_push($posts, (object)$post);
        }

        return array(
            'posts' => $posts,
            'user' => $baseData['user'],
            'tweet_count' => $baseData['tweet_count'],
            'follower_count' => $baseData['follower_count'],
            'following_count' => $baseData['following_count'],
            'liked' => $liked,
        );
    }

    public function getFollowers($username)
    {
        $baseData = $this->getBaseDetails($username);
        $followers = $baseData['user']->followers()->get();
        return array(
            'user' => $baseData['user'],
            'people' => $followers,
            'tweet_count' => $baseData['tweet_count'],
            'follower_count' => $baseData['follower_count'],
            'following_count' => $baseData['following_count'],
        );
    }

    public function getFollowing($username)
    {
        $baseData = $this->getBaseDetails($username);
        $followers = $baseData['user']->following()->get();
        return array(
            'user' => $baseData['user'],
            'people' => $followers,
            'tweet_count' => $baseData['tweet_count'],
            'follower_count' => $baseData['follower_count'],
            'following_count' => $baseData['following_count'],
        );
    }
}

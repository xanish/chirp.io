<?php
namespace App\ServiceObjects;

use App\User;
use App\Tweet;
use Carbon\Carbon;
use \Config;
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

        public function getTweets($username, $lastid)
        {
            if(Auth::user()) {
              $liked = Auth::user()->likes()->pluck('tweet_id')->toArray();
            }
            else {
              $liked = -1;
            }
            if($lastid != '') {
              $baseData = $this->getBaseDetails($username);
              $tweets = $baseData['user']->tweets()
                                         ->where('id', '<', $lastid)
                                         ->take(20)->get();
            }
            else {
              $baseData = $this->getBaseDetails($username);
              $tweets = $baseData['user']->tweets()
                                         ->take(20)->get();
            }

            $posts = [];
            foreach ($tweets as $tweet) {
              $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
              $tweet->text = str_replace("\n", " ", $tweet->text);
              $post = array(
              'id' => $tweet->id,
              'text' => explode(" ", $tweet->text),
              'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
              'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
              'created_at' => $tweet->created_at->toDayDateTimeString(),
              'likes' => $tweet->likes()->count(),
              'tags' => $tweet->hashtags()->pluck('tag')->toArray(),
              );
              array_push($posts, (object)$post);
            }

            return array(
              'posts' => $posts,
              'liked' => $liked,
              'user' => [
                'name' => $baseData['user']->name,
                'username' => $baseData['user']->username,
                'profile_image' => Config::get("constants.avatars").$baseData['user']->profile_image,
                ],
            );
          }


    public function getProfile($username)
    {
        $baseData = $this->getBaseDetails($username);
        return array(
            'user' => $baseData['user'],
            'tweet_count' => $baseData['tweet_count'],
            'follower_count' => $baseData['follower_count'],
            'following_count' => $baseData['following_count'],
        );
    }

    public function getFollowers($username)
    {
        $baseData = $this->getBaseDetails($username);
        $followers = $baseData['user']->followers()->orderBy('name')->get();
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
        $followers = $baseData['user']->following()->orderBy('name')->get();
        return array(
            'user' => $baseData['user'],
            'people' => $followers,
            'tweet_count' => $baseData['tweet_count'],
            'follower_count' => $baseData['follower_count'],
            'following_count' => $baseData['following_count'],
        );
    }
}

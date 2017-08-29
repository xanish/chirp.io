<?php
namespace App\ServiceObjects;

use App\User;
use App\Tweet;
use Carbon\Carbon;
use \Config;

class UserProfileServiceObject
{
    private function getBaseDetails($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return array(
            'user' => $user,
            'tweet_count' => $user->tweets()->count(),
            'follower_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        );
    }

        public function getTweets($username, $lastid)
        {
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
              $post = array(
              'id' => $tweet->id,
              'text' => nl2br(e($tweet->text)),
              'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
              'created_at' => $tweet->created_at->diffForHumans(),
              'likes' => $tweet->likes()->count(),
              );
              array_push($posts, (object)$post);
            }

            return array(
              'posts' => $posts,
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
        $tweets = $baseData['user']->tweets()->get();
        $posts = [];
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

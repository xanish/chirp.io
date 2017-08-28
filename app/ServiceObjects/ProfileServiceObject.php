<?php
namespace App\ServiceObjects;

use Auth;
use App\Tweet;
use App\Follower;
use App\Utils\FeedGenerator;

class ProfileServiceObject {
  public function getProfile($user)
  {
    $showFollows = '';
    try {
      $follower_count = (new Follower)->getFollowersCount($user->id);
      $following_count = (new Follower)->getFollowingsCount($user->id);
      if (Auth::guest()) {
        $showFollows = '';
      }
      else {
        $showFollows = (new Follower)->doesUserFollowsPerson(Auth::user()->id, $user->id);
      }
      $tweets = (new Tweet)->getTweets($user->id);
      $tweet_count = (new Tweet)->getTweetsCount($user->id);
    } catch (Exception $e) {
      throw new Exception("Unable To Get User Profile");
    }
    return array('showFollows' => $showFollows, 'follower_count' => $follower_count, 'following_count' => $following_count, 'tweets' => $tweets, 'tweet_count' => $tweet_count);
  }

  public function getFeed($user)
  {
    try {
      $tweet_count = (new Tweet)->getTweetsCount($user->id);
      $follower_count = (new Follower)->getFollowersCount($user->id);
      $following_count = (new Follower)->getFollowingsCount($user->id);
      $feed = (new FeedGenerator)->generate($user->id);
    } catch (Exception $e) {
      throw new Exception("Unable To Get Users Feed");
    }
    return array('tweet_count' => $tweet_count, 'follower_count' => $follower_count, 'following_count' => $following_count, 'feed' => $feed);
  }
}

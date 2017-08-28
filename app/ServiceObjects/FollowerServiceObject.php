<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Tweet;
use App\Follower;

class FollowerServiceObject {
  public function getFollowersList($user)
  {
    $showFollows;
    try {
      if ($user->username == Auth::user()->username) {
        $showFollows = 'Own Profile';
      }
      else {
        $showFollows = (new Follower)->doesUserFollowsPerson(Auth::user()->id, $user->id);
      }
      $data = (new Follower)->getFollowers($user->id);
      $tweet_count = (new Tweet)->getTweetsCount($user->id);
      $follower_count = count($data);
      $following_count = (new Follower)->getFollowingsCount($user->id);
    } catch (Exception $e) {
      throw new Exception("Unable To Retrieve Followers List");
    }

    return array('data' => $data, 'showFollows' => $showFollows, 'tweet_count' => $tweet_count, 'follower_count' => $follower_count, 'following_count' => $following_count);
  }

  public function getFollowingsList($user)
  {
    $showFollows;
    try {
      if ($user->username == Auth::user()->username) {
        $showFollows = 'Own Profile';
      }
      else {
        $showFollows = (new Follower)->doesUserFollowsPerson(Auth::user()->id, $user->id);
      }
      $data = (new Follower)->getFollowings($user->id);
      $tweet_count = (new Tweet)->getTweetsCount($user->id);
      $following_count = count($data);
      $follower_count = (new Follower)->getFollowersCount($user->id);
    } catch (Exception $e) {
      throw new Exception("Unable To Retrieve Followings List");
    }
    return array('data' => $data, 'showFollows' => $showFollows, 'tweet_count' => $tweet_count, 'follower_count' => $follower_count, 'following_count' => $following_count);
  }

  public function addNewFollower($following)
  {
    try {
      $data = (new User)->getUserId($following);
      $exists = (new Follower)->entryExists(Auth::user()->id, $data);
      if (count($exists) == 1) {
        (new Follower)->setToFollow(Auth::user()->id, $data);
      }
      else {
        (new Follower)->addFollower(Auth::user()->id, $data);
      }
    } catch (Exception $e) {
      throw new Exception("Unable To Add Follower");
    }
  }

  public function updateFollower($follower)
  {
    try {
      $data = (new User)->getUserId($follower);
      (new Follower)->setToUnfollow(Auth::user()->id, $data);
    } catch (Exception $e) {
      throw new Exception("Unable To Update Follower Details");
    }
  }
}

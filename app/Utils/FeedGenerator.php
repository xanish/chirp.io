<?php

namespace App\Utils;

use App\Follower;
use App\Tweet;

class FeedGenerator
{
    public function generate($userid)
    {
        $following = (new Follower)->getFollowings($userid);
        $unfollowed = (new Follower)->getUnfollowed($userid);
        $followingids = [];
        $unfollowedids = [];
        foreach ($following as $person) {
            array_push($followingids, $person->following);
        }
        foreach ($unfollowed as $person) {
            array_push($unfollowedids, $person->following);
        }
        $feed = (new Tweet)->getTweetsFromPeople($followingids, $unfollowedids);
        return $feed;
    }
}

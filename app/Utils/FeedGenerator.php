<?php

namespace App\Utils;

use App\Follower;
use App\Tweet;

class FeedGenerator
{
    public function generate($userid)
    {
        $following = (new Follower)->getPeopleFollowedByUser($userid);
        $unfollowed = (new Follower)->getPeopleUnfollowedByUser($userid);
        $followingids = [];
        $unfollowedids = [];
        foreach ($following as $person) {
            array_push($followingids, $person->following);
        }
        foreach ($unfollowed as $person) {
            array_push($unfollowedids, $person->following);
        }
        $feed = (new Tweet)->getTweetsForMultipleIds($followingids, $unfollowedids);
        return $feed;
    }
}

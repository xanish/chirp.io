<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Follower extends Model
{
    protected $fillable = [
        'user_id', 'following',
    ];

    public function getPeopleFollowedByUser ($userid)
    {
        $data = Follower::where('user_id', $userid)
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->join('users', 'followers.following', '=', 'users.id')
            ->get();
        return $data;
    }

    public function getPeopleFollowingUser ($userid)
    {
        $data = Follower::where('following', $userid)
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->join('users', 'followers.user_id', '=', 'users.id')
            ->get();
        return $data;
    }

    public function userFollowsPerson ($userid, $personid)
    {
        if ($userid == $personid) {
            return 'N/A';
        }
        $data = Follower::where([['user_id', $userid], ['following', $personid]])
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->get();
        if (count($data) == 1) {
            return 'true';
        }
        return 'false';
    }

    public function updateStatusToUnfollow ($userid, $unfollowid)
    {
        $entry = Follower::where([['user_id', $userid], ['following', $unfollowid]])
            ->update(['updated_at' => Carbon::now()]);
    }

    public function getFollowersCount ($userid)
    {
        return count(Follower::getPeopleFollowingUser($userid));
    }

    public function getFollowingsCount ($userid)
    {
        return count(Follower::getPeopleFollowedByUser($userid));
    }
}

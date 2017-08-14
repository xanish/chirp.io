<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;

class Follower extends Model
{
    protected $fillable = [
        'user_id', 'following',
    ];

    public function getPeopleFollowedByUser($username)
    {
        $data = Follower::where('user_id', (new User)->getUserId($username))
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->join('users', 'followers.following', '=', 'users.id')
            ->get();
        return $data;
    }

    public function getPeopleFollowingUser ($username)
    {
        $data = Follower::where('following', (new User)->getUserId($username))
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->join('users', 'followers.user_id', '=', 'users.id')
            ->get();
        return $data;
    }

    public function authUserFollowsPerson($username)
    {
        if ($username == Auth::user()->username) {
            return 'N/A';
        }
        $data = Follower::where([['user_id', Auth::user()->id], ['following', (new User)->getUserId($username)]])
            ->where(function ($query) {
                $query->whereColumn('followers.updated_at', 'followers.created_at');
            })
            ->get();
        if (count($data) == 1) {
            return 'true';
        }
        return 'false';
    }

    public function getFollowersCount ($username)
    {
        return count(Follower::getPeopleFollowingUser($username));
    }

    public function getFollowingsCount ($username)
    {
        return count(Follower::getPeopleFollowedByUser($username));
    }
}

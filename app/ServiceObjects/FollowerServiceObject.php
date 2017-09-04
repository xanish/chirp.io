<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use Exception;

class FollowerServiceObject {
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function follow ($uid)
    {
        try {
            $follow = $this->user->find($uid);
            $id = Auth::id();
            $user = $this->user->find($id);
            $user->following()->attach($follow->id);
        } catch (Exception $e) {
            throw new Exception("Unable To Add Follower");
        }

        return response()->json(200);
    }

    public function unfollow ($uid)
    {
        try {
            $unfollow = $this->user->find($uid);
            $id = Auth::id();
            $user = $this->user->find($id);
            $user->following()->detach($unfollow->id);
        } catch (Exception $e) {
            throw new Exception("Unable To Remove Follower");
        }
        return response()->json(200);
    }
}

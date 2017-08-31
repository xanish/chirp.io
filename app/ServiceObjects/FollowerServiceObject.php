<?php
namespace App\ServiceObjects;

use Auth;
use App\User;

class FollowerServiceObject {
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function follow ($uid)
    {
        $follow = $this->user->find($uid);
        $id = Auth::id();
        $user = $this->user->find($id);
        $user->following()->attach($follow->id);
        return response()->json(200);
    }

    public function unfollow ($uid)
    {
        $unfollow = $this->user->find($uid);
        $id = Auth::id();
        $user = $this->user->find($id);
        $user->following()->detach($unfollow->id);
        return response()->json(200);
    }
}

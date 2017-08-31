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

    public function follow ($username)
    {
        $follow = $this->user->where('username', $username)->firstOrFail();
        $id = Auth::id();
        $user = $this->user->find($id);
        $user->following()->attach($follow->id);
    }

    public function unfollow ($username)
    {
        $unfollow = $this->user->where('username', $username)->firstOrFail();
        $id = Auth::id();
        $user = $this->user->find($id);
        $user->following()->detach($unfollow->id);
    }
}

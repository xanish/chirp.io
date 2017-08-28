<?php
namespace App\ServiceObjects;

use Auth;
use App\User;

class FollowerServiceObject {
  public function follow ($username)
  {
      $follow = User::where('username', $username)->firstOrFail();
      $id = Auth::id();
      $user = User::find($id);
      $user->following()->attach($follow->id);
  }

  public function unfollow ($username)
  {
      $unfollow = User::where('username', $username)->firstOrFail();
      $id = Auth::id();
      $user = User::find($id);
      $user->following()->detach($unfollow->id);
  }
}

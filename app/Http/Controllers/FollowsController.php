<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follower;
use App\User;
use Auth;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function followers()
    {
        $append = false;
        $header = "Followers";
        $data = Follower::where('following', Auth::user()->id)->join('users', 'followers.user_id', '=', 'users.id')->select('username', 'name', 'follower_count', 'following_count')->get();
        // DB::table('followers')->join('users', function ($join) {
        //     $join->on('followers.user_id', '=', 'users.id')->where('followers.following', Auth::user()->id);
        // })->get();
        $user = Auth::user();
        return view('follows', compact('data', 'header', 'user', 'append'));
    }

    public function following()
    {
        $append = false;
        $header = "Following";
        $data = Follower::where('user_id', Auth::user()->id)->join('users', 'followers.following', '=', 'users.id')->get();
        // $following_users = DB::table('followers')->join('users', function ($join) {
        //     $join->on('followers.following', '=', 'users.id')->where('followers.user_id', Auth::user()->id);
        // })->get();
        $user = Auth::user();
        return view('follows', compact('data', 'header', 'user', 'append'));
    }

    public function followersForUser($username)
    {
        $header = "Followers";
        $append = true;
        $user = User::where('username', $username)->firstOrFail();
        $data = Follower::where('following', $user->id)->join('users', 'followers.user_id', '=', 'users.id')->select('username', 'name', 'follower_count', 'following_count')->get();
        return view('follows', compact('data', 'header', 'user', 'append'));
    }

    public function followingForUser($username)
    {
        $header = "Following";
        $append = true;
        $user = User::where('username', $username)->firstOrFail();
        $data = Follower::where('user_id', $user->id)->join('users', 'followers.following', '=', 'users.id')->select('username', 'name', 'follower_count', 'following_count')->get();
        return view('follows', compact('data', 'header', 'user', 'append'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Follower;
use App\User;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $header = "Followers";
        $append = false;
        $user = Auth::user();
        $showFollows = 'Own Profile';
        $data = (new Follower)->getPeopleFollowingUser($user->username);
        $follower_count = count($data);
        $following_count = (new Follower)->getFollowingsCount($user->username);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'follower_count', 'following_count'));
    }

    public function show($username)
    {
        $header = "Followers";
        $append = true;
        $user = (new User)->getUserByUsername($username);
        $showFollows = (new Follower)->authUserFollowsPerson($username);
        $data = (new Follower)->getPeopleFollowingUser($user->username);
        $follower_count = count($data);
        $following_count = (new Follower)->getFollowingsCount($user->username);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'follower_count', 'following_count'));
    }
}

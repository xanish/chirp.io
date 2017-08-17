<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Follower;
use App\User;
use App\Tweet;

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
        $data = (new Follower)->getPeopleFollowingUser($user->id);
        $tweet_count = (new Tweet)->getTweetCountForPerson($user->id);
        $follower_count = count($data);
        $following_count = (new Follower)->getFollowingsCount($user->id);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'tweet_count', 'follower_count', 'following_count'));
    }

    public function show($username)
    {
        $header = "Followers";
        $append = true;
        $user = (new User)->getUserByUsername($username);
        $showFollows = (new Follower)->userFollowsPerson(Auth::user()->id, $user->id);
        $data = (new Follower)->getPeopleFollowingUser($user->id);
        $tweet_count = (new Tweet)->getTweetCountForPerson($user->id);
        $follower_count = count($data);
        $following_count = (new Follower)->getFollowingsCount($user->id);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'tweet_count', 'follower_count', 'following_count'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Follower;
use App\Tweet;

class ProfileController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $append = true;
        $showFollows = '';
        $follower_count = (new Follower)->getFollowersCount($username);
        $following_count = (new Follower)->getFollowingsCount($username);
        $tweets = Tweet::where('posted_by', $user->id);
        if (Auth::guest()) {
            $showFollows = '';
        }
        else {
            $showFollows = (new Follower)->authUserFollowsPerson($username);
        }
        return view('home', compact('user', 'append', 'showFollows', 'follower_count', 'following_count'));
    }
}

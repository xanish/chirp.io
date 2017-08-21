<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Follower;
use App\Tweet;

class FollowingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $header = "Following";
        $append = false;
        $user = Auth::user();
        $showFollows = 'Own Profile';
        $data = (new Follower)->getFollowings($user->id);
        $tweet_count = (new Tweet)->getTweetsCount($user->id);
        $following_count = count($data);
        $follower_count = (new Follower)->getFollowersCount($user->id);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'tweet_count', 'follower_count', 'following_count'));
    }

    public function store(Request $request)
    {
        $data = (new User)->getUserId($request->following);
        $exists = Follower::where([['user_id', Auth::user()->id], ['following', $data]])->get();
        if (count($exists) == 1) {
            (new Follower)->setToFollow(Auth::user()->id, $data);
        }
        else {
            (new Follower)->addFollower(Auth::user()->id, $data);
        }
        return redirect('following');
    }

    public function update($follower)
    {
        $data = (new User)->getUserId($follower);
        (new Follower)->setToUnfollow(Auth::user()->id, $data);
        return redirect('/following');
    }

    public function show($username)
    {
        $header = "Following";
        $append = true;
        $user = (new User)->getUserByUsername($username);
        $showFollows = (new Follower)->doesUserFollowsPerson(Auth::user()->id, $user->id);
        $data = (new Follower)->getFollowings($user->id);
        $tweet_count = (new Tweet)->getTweetsCount($user->id);
        $following_count = count($data);
        $follower_count = (new Follower)->getFollowersCount($user->id);
        return view('follows', compact('header', 'append', 'user', 'data', 'showFollows', 'tweet_count', 'follower_count', 'following_count'));
    }
}

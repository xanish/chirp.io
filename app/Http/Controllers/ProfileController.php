<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DateFormatter;
use Carbon\Carbon;
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

    public function viewfeed()
    {
        $tweet = new Tweet;
        $formatted_feeds = new DateFormatter;
        $feeds = $tweet->getTweets();
        $feeds = $formatted_feeds->formatDate($feeds);

        return view('profile', compact('feeds'));
    }
}

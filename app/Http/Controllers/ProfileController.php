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
        $user = (new User)->getUserByUsername($username);
        $append = true;
        $showFollows = '';
        $follower_count = (new Follower)->getFollowersCount($user->id);
        $following_count = (new Follower)->getFollowingsCount($user->id);
        if (Auth::guest()) {
            $showFollows = '';
        }
        else {
            $showFollows = (new Follower)->doesUserFollowsPerson(Auth::user()->id, $user->id);
        }
        $tweets = (new Tweet)->getTweets($user->id);
        $tweet_count = count($tweets);
        return view('profile', compact('user', 'append', 'showFollows', 'tweet_count', 'follower_count', 'following_count', 'tweets'));
    }

    public function ajaxfeed()
    {
      $user = Auth::user();
      $tweets = (new Tweet)->getTweets($user->id);
      return view('ajaxfeed', compact('tweets','user'));
    }
}

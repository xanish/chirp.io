<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Follower;
use App\Tweet;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = Auth::user();
    $following = (new Follower)->getPeopleFollowedByUser($user->username);
    $follower_count = (new Follower)->getFollowersCount($user->username);
    $following_count = count($following);
    $a = [];
    foreach ($following as $person) {
        array_push($a, $person->following);
    }
    $tweets = (new Tweet)->getTweets($user->username);
    return view('home', compact('user', 'follower_count', 'following_count', 'tweets'));
  }
}

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
    $following = (new Follower)->getPeopleFollowedByUser($user->id);
    $follower_count = (new Follower)->getFollowersCount($user->id);
    $following_count = count($following);
    $ids = [];
    foreach ($following as $person) {
        array_push($ids, $person->following);
    }
    $feed = (new Tweet)->getTweetsForMultipleIds($ids);
    $tweet_count = (new Tweet)->getTweetCountForPerson($user->id);
    return view('home', compact('user', 'follower_count', 'following_count', 'tweet_count', 'feed'));
  }
}

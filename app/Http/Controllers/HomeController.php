<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Follower;
use App\Tweet;
use App\Utils\FeedGenerator;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = Auth::user();
    $tweet_count = (new Tweet)->getTweetCountForPerson($user->id);
    //$tweet_count = (new Tweet)->getTweetCountForMultipleIds($ids);
    $follower_count = (new Follower)->getFollowersCount($user->id);
    $following_count = (new Follower)->getFollowingsCount($user->id);
    $feed = (new FeedGenerator)->generate($user->id);
    return view('home', compact('user', 'follower_count', 'following_count', 'tweet_count', 'feed'));
  }
}

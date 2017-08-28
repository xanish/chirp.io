<?php

namespace App\Http\Controllers;

use App\User;
use App\Tweet;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id = Auth::id();
        $followingids = [];
        $user = User::find($id);
        $tweet_count = $user->tweets()->count();
        $follower_count = $user->followers()->count();
        $following_count = $user->following()->count();
        $following = $user->following()->get();
        foreach ($following as $person) {
            array_push($followingids, $person->id);
        }
        $feed = Tweet::whereIn('user_id', $followingids)
        ->join('users', 'tweets.user_id', '=', 'users.id')
        ->select('users.name', 'users.username', 'users.profile_image', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at')
        ->latest()->get();
        return view('home', compact('user', 'tweet_count', 'following_count', 'follower_count', 'feed'));
    }
}

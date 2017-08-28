<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'profile'
        ]);
    }

    public function profile(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $tweets = $user->tweets()->get();
        $tweet_count = $user->tweets()->count();
        $follower_count = $user->followers()->count();
        $following_count = $user->following()->count();
        $path = $request->path();
        return view('tweets', compact('user', 'tweets', 'tweet_count', 'follower_count', 'following_count', 'path', 'accent'));
    }

    public function followers(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $followers_count = $user->followers()->count();
        $following_count = $user->following()->count();
        $tweet_count = $user->tweets()->count();
        $people = $user->followers()->orderBy('username')->get();
        $path = $request->path();
        return view('follows', [
            'user' => $user,
            'follower_count' => $followers_count,
            'following_count' => $following_count,
            'tweet_count' => $tweet_count,
            'people' => $people,
            'path' => $path,
        ]);
    }

    public function following(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $followers_count = $user->followers()->count();
        $following_count = $user->following()->count();
        $tweet_count = $user->tweets()->count();
        $path = $request->path();
        $people = $user->following()->orderBy('username')->get();
        return view('follows', [
            'user' => $user,
            'follower_count' => $followers_count,
            'following_count' => $following_count,
            'tweet_count' => $tweet_count,
            'people' => $people,
            'path' => $path,
        ]);
    }

}

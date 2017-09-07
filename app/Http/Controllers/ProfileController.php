<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\UserProfileServiceObject;
use App\Follower;
class ProfileController extends Controller
{
    private $profileSO;

    public function __construct(UserProfileServiceObject $profileSO)
    {
        $this->middleware('auth')->except([
            'profile',
            'fetchTweets'
        ]);
        $this->profileSO = $profileSO;
    }

    public function fetchTweets(Request $request) {
      $tweets = $this->profileSO->getTweets($request->username, $request->lastid);
      return response($tweets);
    }

    public function profile(Request $request, $username)
    {
        $profileData = $this->profileSO->getProfile($username);
        $path = $request->path();
        return view('tweets')->with([
            'user' => $profileData['user'],
            'tweet_count' => $profileData['tweet_count'],
            'follower_count' => $profileData['follower_count'],
            'following_count' => $profileData['following_count'],
            'path' => $path,
        ]);
    }

    public function followers(Request $request, $username)
    {
        $followersData = $this->profileSO->followers($username);
        $path = $request->path();
        return view('follows')->with([
            'user' => $followersData['user'],
            'people' => $followersData['people'],
            'tweet_count' => $followersData['tweet_count'],
            'follower_count' => $followersData['follower_count'],
            'following_count' => $followersData['following_count'],
            'path' => $path,
        ]);
    }

    public function following(Request $request, $username)
    {
        $followingData = $this->profileSO->following($username);
        $path = $request->path();
        return view('follows')->with([
            'user' => $followingData['user'],
            'people' => $followingData['people'],
            'tweet_count' => $followingData['tweet_count'],
            'follower_count' => $followingData['follower_count'],
            'following_count' => $followingData['following_count'],
            'path' => $path,
        ]);
    }

}

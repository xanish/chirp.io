<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\FeedServiceObject;

class HomeController extends Controller
{
    private $feedSO;

    public function __construct(FeedServiceObject $feedSO)
    {
        $this->middleware('auth');
        $this->feedSO = $feedSO;
    }

    public function index()
    {
        $response = $this->feedSO->getFeed();
        return view('home')->with([
            'user' => $response['user'],
            'posts' => $response['posts'],
            'tweet_count' => $response['tweet_count'],
            'follower_count' => $response['follower_count'],
            'following_count' => $response['following_count'],
        ]);
    }
}

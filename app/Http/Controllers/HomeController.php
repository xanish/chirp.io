<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $response = $this->feedSO->getUser();
        return view('home')->with([
            'user' => $response['user'],
            'tweet_count' => $response['tweet_count'],
            'follower_count' => $response['follower_count'],
            'following_count' => $response['following_count'],
            'liked' => $response['liked'],
        ]);
    }

    public function getfeed(Request $request)
    {
        $response = $this->feedSO->getFeed($request->lastid);
        return response($response);
    }
}

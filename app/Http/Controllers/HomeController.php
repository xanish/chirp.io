<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ServiceObjects\FeedServiceObject;
use App\Utils\Utils;

class HomeController extends Controller
{
    private $feedSO;
    private $utils;

    public function __construct(FeedServiceObject $feedSO, Utils $utils)
    {
        $this->middleware('auth');
        $this->feedSO = $feedSO;
        $this->utils = $utils;
    }

    public function index()
    {
        $response = $this->feedSO->getUser();
        return view('home')->with([
            'user' => $response['user'],
            'tweet_count' => $response['tweet_count'],
            'follower_count' => $response['follower_count'],
            'following_count' => $response['following_count'],
        ]);
    }

    public function getfeed(Request $request)
    {
        $response = $this->feedSO->getFeed($request->lastid, $request->currentid);
        return response($response);
    }
}

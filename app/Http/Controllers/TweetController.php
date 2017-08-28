<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTweetRequest;
use App\ServiceObjects\TweetServiceObject;

class TweetController extends Controller
{
    public function __construct(TweetServiceObject $tweetSO)
    {
        $this->middleware('auth');
        $this->tweetSO = $tweetSO;
    }

    public function create(PostTweetRequest $request)
    {
        $response = $this->tweetSO->postTweet($request);
        return response()->json([
          'element' => [
            'text' => $response['text'],
            'image' => $response['image'],
            'date' => $response['date'],
            'name' => $response['name'],
            'username' => $response['username'],
            'avatar' => $response['avatar'],
          ]
        ]);
    }
}

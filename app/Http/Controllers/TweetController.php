<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTweetRequest;
use App\ServiceObjects\TweetServiceObject;
use App\ServiceObjects\HashtagServiceObject;

class TweetController extends Controller
{
    private $tweetSO;
    private $hashtagSO;

    public function __construct(TweetServiceObject $tweetSO, HashtagServiceObject $hashtagSO)
    {
        $this->middleware('auth');
        $this->tweetSO = $tweetSO;
        $this->hashtagSO = $hashtagSO;
    }

    public function create(PostTweetRequest $request)
    {
        $response = $this->tweetSO->postTweet($request);
        $tags = json_decode($request->hashtags);
        if(count($tags)) {
            $this->hashtagSO->addTags($tags, $response['tweet_id']);
        }
        return response()->json([
            'element' => [
                'id' => $response['tweet_id'],
                'text' => $response['text'],
                'image' => $response['image'],
                'date' => $response['date'],
                'name' => $response['name'],
                'username' => $response['username'],
                'avatar' => $response['avatar'],
                'tags' => $tags,
            ]
        ]);
    }
}

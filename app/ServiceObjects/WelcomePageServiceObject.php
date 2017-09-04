<?php
namespace App\ServiceObjects;

use App\Hashtag;
use App\Tweet;
use Exception;

class WelcomePageServiceObject
{
    private $tweet;
    private $hashtag;

    public function __construct(Tweet $tweet, Hashtag $hashtag)
    {
        $this->tweet = $tweet;
        $this->hashtag = $hashtag;
    }

    public function welcomePageData()
    {
        $latest_tweets = $this->tweet->latest()->limit(10)->get();
        $tweets = [];
        foreach ($latest_tweets as $tweet) {
            $t = [
                'text' => $tweet->text,
                'tweet_image' => $tweet->tweet_image,
                'created_at' => $tweet->created_at,
                'likes' => $tweet->likes()->count(),
                'user' => $tweet->user()->firstOrFail(),
            ];
            array_push($tweets, (object)$t);
        }
        return [
            'popular_tags' => $this->hashtag->selectRaw('tag, count(tag) as tag_count')->groupBy('tag')->orderBy('tag_count', 'DESC')->limit(10)->get(),
            'latest_tweets' => $tweets,
        ];
    }
}

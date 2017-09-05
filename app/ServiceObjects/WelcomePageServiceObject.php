<?php
namespace App\ServiceObjects;

use App\Hashtag;
use App\Tweet;
use Exception;
use \Config;

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
            $temp =  $tweet->hashtags()->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $t = [
                'text' => nl2br(e($tweet->text)),
                'tweet_image' => $tweet->tweet_image,
                'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                'created_at' => $tweet->created_at,
                'likes' => $tweet->likes()->count(),
                'user' => $tweet->user()->firstOrFail(),
                'tags' => $tags,
            ];
            array_push($tweets, (object)$t);
        }
        return [
            'popular_tags' => $this->hashtag->selectRaw('tag, count(tag) as tag_count')->groupBy('tag')->orderBy('tag_count', 'DESC')->limit(10)->get(),
            'latest_tweets' => $tweets,
        ];
    }
}

<?php
namespace App\ServiceObjects;

use App\Hashtag;
use App\Tweet;
use Exception;
use \Config;
use Carbon\Carbon;
use App\Like;

class WelcomePageServiceObject
{
    private $tweet;
    private $hashtag;
    private $like;

    public function __construct(Tweet $tweet, Hashtag $hashtag, Like $like)
    {
        $this->tweet = $tweet;
        $this->like = $like;
        $this->hashtag = $hashtag;
    }

    public function welcomePageData()
    {
        $latest_tweets = $this->tweet->latest()->where('tweets.created_at', '>', Carbon::now()->subDays(7))
                                               ->join('users', 'tweets.user_id', '=', 'users.id')
                                               ->select('users.id as uid', 'users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
                                               ->limit(10)->get();
        $ids = $latest_tweets->pluck('id')->toArray();
        $likes = $this->like->all()->whereIn('tweet_id', $ids);
        $tags_collection = $this->hashtag->whereIn('tweet_id', $ids)->get();
        $tweets = [];
        foreach ($latest_tweets as $tweet) {
            $temp =  $tags_collection->where('tweet_id', $tweet->id)->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $t = [
                'text' => explode(' ', $tweet->text),
                'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                'created_at' => $tweet->created_at,
                'likes' => $likes->where('tweet_id', $tweet->id)->count(),
                'id' => $tweet->id,
                'user_id' => $tweet->uid,
                'name' => $tweet->name,
                'username' => $tweet->username,
                'profile_image' => Config::get("constants.avatars").$tweet->profile_image,
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

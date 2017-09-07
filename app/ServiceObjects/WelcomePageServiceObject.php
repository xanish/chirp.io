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

    public function getLatestTweets()
    {
        return $this->tweet->latest()
                    ->where('tweets.created_at', '>', Carbon::now()->subDays(2))
                    ->join('users', 'tweets.user_id', '=', 'users.id')
                    ->select('users.id as uid', 'users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
                    ->limit(10)
                    ->get();
    }

    public function tagsToArray($tags_collection, $id)
    {
        return $tags_collection->where('tweet_id', $id)->pluck('tag')->toArray();
    }

    public function createHashtag($tags)
    {
        $hashtags = [];
        foreach ($tags as $tag) {
            array_push($hashtags, '#'.$tag);
        }
        return $hashtags;
    }

    public function likeCount($likes, $id)
    {
        return $likes->where('tweet_id', $id)->count();
    }

    public function parseTweets($latest_tweets, $tags_collection, $likes)
    {
        $tweets = [];

        foreach ($latest_tweets as $tweet) {
            $temp = $this->tagsToArray($tags_collection, $tweet->id);
            $tags = $this->createHashtag($temp);

            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);

            $t = [
                'text' => explode(' ', $tweet->text),
                'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                'created_at' => $tweet->created_at,
                'likes' => $this->likeCount($likes, $tweet->id),
                'id' => $tweet->id,
                'user_id' => $tweet->uid,
                'name' => $tweet->name,
                'username' => $tweet->username,
                'profile_image' => Config::get("constants.avatars").$tweet->profile_image,
                'tags' => $tags,
            ];
            array_push($tweets, (object)$t);
        }
        return $tweets;
    }

    public function welcomePageData()
    {
        $latest_tweets = $this->getLatestTweets();

        $ids = $latest_tweets->pluck('id')->toArray();
        $likes = $this->like->tweets($ids);

        $tags = $this->hashtag->tweets($ids);

        $tweets = $this->parseTweets($latest_tweets, $tags, $likes);
        return [
            'popular_tags' => $this->hashtag->popular(),
            'latest_tweets' => $tweets,
        ];
    }
}

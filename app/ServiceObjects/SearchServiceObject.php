<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Tweet;
use App\Follower;
use App\Hashtag;

class SearchServiceObject
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getSearchResults($criteria)
    {
        try {
            $data = $this->user->where('name', 'LIKE', '%'.$criteria.'%')
                ->orWhere('username', 'LIKE', '%'.$criteria.'%')
                ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image', 'profile_banner')
                ->orderBy('name')
                ->get();
        } catch (Exception $e) {
            throw new Exception("Unable To Get Search Results From DB");
        }
        return $data;
    }

    public function getSearchResultsForPage($criteria)
    {
        $ids = [];
        try {
            $data = $this->getSearchResults($criteria);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        if (Auth::user()) {
            $user = Auth::user();
            $ids = $user->following()->pluck('follows')->toArray();
        }
        return array('data' => $data, 'ids' => $ids);
    }

    public function getTweetsByTag($tag)
    {
        $tweets = Hashtag::where('tag', '#'.$tag)
            ->join('tweets', 'tweets.id', '=', 'hashtags.tweet_id')
            ->join('users', 'users.id', '=', 'tweets.user_id')
            ->select('users.username', 'users.name', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.created_at')
            ->get();
        $posts = [];
        foreach($tweets as $tweet) {
            $post = array(
                'username' => $tweet->username,
                'name' => $tweet->name,
                'profile_image' => $tweet->profile_image,
                'id' => $tweet->id,
                'text' => explode(' ', $tweet->text),
                'tweet_image' => $tweet->tweet_image,
                'likes' => Tweet::find($tweet->id)->likes()->count(),
                'tags' => Tweet::find($tweet->id)->hashtags()->pluck('tag')->toArray(),
                'created_at' => $tweet->created_at,
            );
            array_push($posts, (object)$post);
        }
        return array(
            'posts' => $posts,
            'liked' => Auth::user()->likes()->pluck('tweet_id')->toArray(),
        );
    }
}

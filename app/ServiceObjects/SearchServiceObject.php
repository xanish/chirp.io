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
                ->limit(10)
                ->get();
        } catch (Exception $e) {
            throw new Exception("Unable To Get Search Results From DB");
        }
        return $data;
    }

    public function getSearchResultsPaginated($criteria)
    {
        try {
            $data = $this->user->where('name', 'LIKE', '%'.$criteria.'%')
                ->orWhere('username', 'LIKE', '%'.$criteria.'%')
                ->select('id', 'name', 'username', 'birthdate', 'city', 'country', 'created_at', 'profile_image', 'profile_banner')
                ->orderBy('name')
                ->paginate(24);
        } catch (Exception $e) {
            throw new Exception("Unable To Get Search Results From DB");
        }
        return $data;
    }

    public function getSearchResultsForPage($criteria)
    {
        $ids = [];
        try {
            $data = $this->getSearchResultsPaginated($criteria);
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
        $tweets = Hashtag::where('tag', $tag)
            ->join('tweets', 'tweets.id', '=', 'hashtags.tweet_id')
            ->join('users', 'users.id', '=', 'tweets.user_id')
            ->select('users.username', 'users.name', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
            ->orderBy('tweets.id', 'DESC')->get();
        $posts = [];
        foreach($tweets as $tweet) {
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $temptags = Tweet::find($tweet->id)->hashtags()->pluck('tag')->toArray();
            $tags = [];
            foreach ($temptags as $tag) {
                array_push($tags, '#'.$tag);
            }
            $post = array(
                'username' => $tweet->username,
                'name' => $tweet->name,
                'profile_image' => $tweet->profile_image,
                'id' => $tweet->id,
                'text' => explode(" ", $tweet->text),
                'tweet_image' => $tweet->tweet_image,
                'original_image' => $tweet->original_image,
                'likes' => Tweet::find($tweet->id)->likes()->count(),
                'tags' => $tags,
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

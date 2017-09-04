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
    private $tweet;
    private $hashtag;

    public function __construct(User $user, Tweet $tweet, Hashtag $hashtag)
    {
        $this->user = $user;
        $this->tweet = $tweet;
        $this->hashtag = $hashtag;
    }

    public function getSearchResults($criteria)
    {
        try {
            $users = $this->user->findUsers($criteria)->get();
            $tags = $this->hashtag->findTags(ltrim($criteria, '#'))->get();
        } catch (Exception $e) {
            throw new Exception("Unable To Get Search Results From DB");
        }
        return array(
            "users" => $users,
            "tags" => $tags,
        );
    }

    public function getSearchResultsPaginated($criteria)
    {
        try {
            $data = $this->user->findUsers($criteria)->paginate(24);
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
        $tweets = $this->hashtag->getTweetsForTag($tag)->get();
        $posts = [];
        foreach($tweets as $tweet) {
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $temptags = $this->tweet->getTags($tweet->id);
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
                'likes' => $this->tweet->likeCount($tweet->id),
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

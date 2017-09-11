<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Tweet;
use App\Like;
use App\Follower;
use App\Hashtag;
use \Config;
use Carbon\Carbon;
use App\Populartag;

class SearchServiceObject
{
    private $user;
    private $tweet;
    private $like;
    private $hashtag;

    public function __construct(User $user, Tweet $tweet, Hashtag $hashtag, Like $like)
    {
        $this->user = $user;
        $this->like = $like;
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
            $ids = (new Follower)->users('user_id = '. $user->id .' and created_at = updated_at');
            $ids = $ids->pluck('follows')->toArray();
        }
        return array('data' => $data, 'ids' => $ids);
    }

    public function getTweetsByTag($tag, $lastid)
    {
        $tweets = $this->hashtag->getTweetsForTag($tag, $lastid);
        $ids = $tweets->pluck('id')->toArray();
        $likes = $this->like->tweets($ids);
        $tags_collection = $this->hashtag->tweets($ids)->pluck('tag')->unique()->values()->toArray();
        $tags = [];
        foreach ($tags_collection as $tag) {
            array_push($tags, '#'.$tag);
        }
        foreach ($tweets as $tweet) {
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $tweet->text = explode(" ", $tweet->text);
            $tweet->tweet_image = Config::get("constants.tweet_images").$tweet->tweet_image;
            $tweet->original_image = Config::get("constants.tweet_images").$tweet->original_image;
            //$tweet->created_at = $tweet->created_at->toDayDateTimeString();
            $tweet->likes = $likes->where('tweet_id', $tweet->id)->count();
            $tweet->profile_image = Config::get("constants.avatars").$tweet->profile_image;
        }
        return array(
            'posts' => $tweets,
            'liked' => Auth::guest() ? [] : $likes->where('user_id', Auth::id())->pluck('tweet_id')->toArray(),
            'tags' => $tags,
        );
    }

    public function popular()
    {
        return Populartag::select('tag', 'tag_count')->get();
    }
}

<?php
namespace App\ServiceObjects;

use Auth;
use App\User;
use App\Tweet;
use App\Like;
use Carbon\Carbon;
use \Config;
use App\Follower;
use App\Hashtag;

class FeedServiceObject
{
    private $user;
    private $like;
    private $tweet;
    private $follower;
    private $hashtag;
    public $followingids;

    public function __construct(User $user, Tweet $tweet, Follower $follower, Like $like, Hashtag $hashtag)
    {
        $this->user = $user;
        $this->like = $like;
        $this->tweet = $tweet;
        $this->follower = $follower;
        $this->hashtag = $hashtag;
    }

    public function getFeed($lastid, $currentid)
    {
        $user = Auth::user();
        $follow = $this->follower->users('user_id = '. $user->id .' or follows = '. $user->id);
        $followingids = $follow->where('user_id', $user->id)->pluck('follows');
        $currentdata = 0;

        if($lastid != '') {
            $feed = $this->tweet->whereIn('user_id', $followingids)
            ->where('tweets.id', '<', $lastid)
            ->join('users', 'tweets.user_id', '=', 'users.id')
            ->select('users.id as uid', 'users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
            ->orderBy('tweets.id', 'DESC')
            ->take(20)->get();
        }
        elseif ($currentid != '') {
            $currentdata = 1;
            $feed = $this->tweet->whereIn('user_id', $followingids)
            ->where('tweets.id', '>', $currentid)
            ->join('users', 'tweets.user_id', '=', 'users.id')
            ->select('users.id as uid', 'users.id', 'users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
            ->orderBy('tweets.id', 'DESC')
            ->get();
        }
        else {
            $feed = Tweet::whereIn('user_id', $followingids)
            ->join('users', 'tweets.user_id', '=', 'users.id')
            ->select('users.id as uid', 'users.name', 'users.username', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
            ->orderBy('tweets.id', 'DESC')
            ->take(20)->get();
        }

        $posts = $this->parseFeed($feed, $follow->where('user_id', $user->id));
        $liked = $posts['likes'];
        $liked = $liked->where('user_id', Auth::id())->pluck('tweet_id')->toArray();
        $posts = $posts['posts'];

        return array(
            'posts' => $posts,
            'liked' => $liked,
            'currentdata' => $currentdata,
        );
    }

    public function getUser()
    {
        $user = Auth::user();
        $tweet_count = $user->tweets()->count();
        $follow = $this->follower->users('(user_id = '. $user->id .' or follows = '. $user->id .') and created_at = updated_at');
        $follower_count = $follow->where('follows', $user->id)->count();
        $following_count = $follow->where('user_id', $user->id)->count();

        return array(
            'user' => $user,
            'tweet_count' => $tweet_count,
            'follower_count' => $follower_count,
            'following_count' => $following_count,
        );
    }

    public function parseFeed($feed, $follow)
    {
        $posts = [];
        $ids = $feed->pluck('id')->toArray();
        $likes = $this->like->whereIn('tweet_id', $ids)->select('tweet_id', 'user_id')->get();
        $tags_collection = $this->hashtag->whereIn('tweet_id', $ids)->select('tag', 'tweet_id')->get();
        foreach ($feed as $tweet) {
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $temp =  $tags_collection->where('tweet_id', $tweet->id)->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            $f = $follow->where('follows', $tweet->uid);
            if ($tweet->created_at < $f->pluck('updated_at')[0] or $f->pluck('created_at')[0] == $f->pluck('updated_at')[0]) {
                $post = array(
                    'id' => $tweet->id,
                    'user_id' => $tweet->uid,
                    'text' => explode(" ", $tweet->text),
                    'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                    'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                    'created_at' => $tweet->created_at->timestamp,
                    'likes' => $likes->where('tweet_id', $tweet->id)->count(),
                    'name' => $tweet->name,
                    'username' => $tweet->username,
                    'profile_image' => Config::get("constants.avatars").$tweet->profile_image,
                    'tags' => $tags,
                );
                array_push($posts, (object)$post);
            }
        }
        return array(
            'posts' => $posts,
            'likes' => $likes
        );
    }
}

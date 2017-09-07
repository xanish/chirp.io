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
        $id = Auth::id();
        $user = $this->user->find($id);
        $tweet_count = $user->tweets()->count();
        $follower_count = $user->followers()->whereColumn('followers.created_at', 'followers.updated_at')->count();
        $following_count = $user->following()->whereColumn('followers.created_at', 'followers.updated_at')->count();
        $followingids = $user->following()->pluck('follows');
        $liked = Auth::user()->likes()->pluck('tweet_id')->toArray();
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

        $posts = $this->parseFeed($feed);

        return array(
            'posts' => $posts,
            'liked' => $liked,
            'currentdata' => $currentdata,
        );
    }

    public function getUser()
    {
        $id = Auth::id();
        $user = User::find($id);
        $tweet_count = $user->tweets()->count();
        $follower_count = $user->followers()->whereColumn('followers.created_at', 'followers.updated_at')->count();
        $following_count = $user->following()->whereColumn('followers.created_at', 'followers.updated_at')->count();

        return array(
            'user' => $user,
            'tweet_count' => $tweet_count,
            'follower_count' => $follower_count,
            'following_count' => $following_count,
        );
    }

    public function parseFeed($feed)
    {
        $posts = [];
        $ids = $feed->pluck('id')->toArray();
        $follow = $this->follower->all()->where('user_id', Auth::user()->id);
        $likes = $this->like->all()->whereIn('tweet_id', $ids);
        $tags_collection = $this->hashtag->whereIn('tweet_id', $ids)->get();
        foreach ($feed as $tweet) {
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $temp =  $tags_collection->where('tweet_id', $tweet->id)->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            $f = $follow->where('follows', $tweet->uid);
            if ($tweet->created_at < $f->get('updated_at') or $f->get('created_at') == $f->get('updated_at')) {
                $post = array(
                    'id' => $tweet->id,
                    'user_id' => $tweet->uid,
                    'text' => explode(" ", $tweet->text),
                    'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                    'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                    'created_at' => $tweet->created_at->toDayDateTimeString(),
                    'likes' => $likes->where('tweet_id', $tweet->id)->count(),
                    'name' => $tweet->name,
                    'username' => $tweet->username,
                    'profile_image' => Config::get("constants.avatars").$tweet->profile_image,
                    'tags' => $tags,
                );
                array_push($posts, (object)$post);
            }
        }
        return $posts;
    }
}

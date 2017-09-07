<?php
namespace App\ServiceObjects;

use App\User;
use App\Tweet;
use Carbon\Carbon;
use \Config;
use Auth;
use App\Like;
use App\Utils\Utils;
use App\Hashtag;
use Exception;

class UserProfileServiceObject
{
    private $user;
    private $utils;
    private $hashtag;
    private $like;

    public function __construct(User $user, Utils $utils, Hashtag $hashtag, Like $like)
    {
        $this->user = $user;
        $this->like = $like;
        $this->hashtag = $hashtag;
        $this->utils = $utils;
    }

    private function getBaseDetails($username)
    {
        try {
            $user = $this->user->where('username', $username)
            ->select(
                'id',
                'name',
                'username',
                'profile_image',
                'profile_banner',
                'city',
                'country',
                'birthdate',
                'created_at'
                )->firstOrFail();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return array(
            'user' => $user,
            'tweet_count' => $user->tweets()->count('tweets.id'),
            'follower_count' => $user->followers()->whereColumn('followers.created_at', 'followers.updated_at')->count('followers.id'),
            'following_count' => $user->following()->whereColumn('followers.created_at', 'followers.updated_at')->count('followers.id'),
        );
    }

    public function getTweets($username, $lastid)
    {
        if(Auth::user()) {
            $liked = Auth::user()->likes()->pluck('tweet_id')->toArray();
        }
        else {
            $liked = -1;
        }
        if($lastid != '') {
            try {
                $baseData = $this->getBaseDetails($username);
                $tweets = $baseData['user']->tweets()
                ->where('id', '<', $lastid)
                ->take(20)->get();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        else {
            try {
                $baseData = $this->getBaseDetails($username);
                $tweets = $baseData['user']->tweets()
                ->take(20)->get();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $posts = [];
        $ids = $tweets->pluck('id')->toArray();
        $tags_collection = $this->hashtag->whereIn('tweet_id', $ids)->get();
        $likes = $this->like->all()->whereIn('tweet_id', $ids);

        foreach ($tweets as $tweet) {
            $temp =  $tags_collection->where('tweet_id', $tweet->id)->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            // $tweet->text =json_encode($tweet->text);
            //$tweet->text = preg_split( '/(<.*>)/u', $tweet->text, -1, PREG_SPLIT_DELIM_CAPTURE );
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $post = array(
                'id' => $tweet->id,
                'text' => explode(" ", $tweet->text),
                'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                'created_at' => $tweet->created_at->toDayDateTimeString(),
                'likes' => $likes->where('tweet_id', $tweet->id)->count(),
                'tags' => $tags,
            );
            array_push($posts, (object)$post);
        }
        return array(
            'posts' => $posts,
            'liked' => $liked,
            'user' => [
                'name' => $baseData['user']->name,
                'username' => $baseData['user']->username,
                'profile_image' => Config::get("constants.avatars").$baseData['user']->profile_image,
                ],
            );
        }


        public function getProfile($username)
        {
            $baseData = $this->getBaseDetails($username);
            return array(
                'user' => $baseData['user'],
                'tweet_count' => $baseData['tweet_count'],
                'follower_count' => $baseData['follower_count'],
                'following_count' => $baseData['following_count'],
            );
        }

        public function getFollowers($username)
        {
            $baseData = $this->getBaseDetails($username);
            $followers = $baseData['user']->followers()->whereColumn('followers.created_at', 'followers.updated_at')->orderBy('name')->get();
            return array(
                'user' => $baseData['user'],
                'people' => $followers,
                'tweet_count' => $baseData['tweet_count'],
                'follower_count' => $baseData['follower_count'],
                'following_count' => $baseData['following_count'],
            );
        }

        public function getFollowing($username)
        {
            $baseData = $this->getBaseDetails($username);
            $followers = $baseData['user']->following()->whereColumn('followers.created_at', 'followers.updated_at')->orderBy('name')->get();
            return array(
                'user' => $baseData['user'],
                'people' => $followers,
                'tweet_count' => $baseData['tweet_count'],
                'follower_count' => $baseData['follower_count'],
                'following_count' => $baseData['following_count'],
            );
        }
    }

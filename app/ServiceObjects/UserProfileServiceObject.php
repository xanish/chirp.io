<?php
namespace App\ServiceObjects;

use App\User;
use Carbon\Carbon;
use \Config;
use Auth;
use App\Follower;
use App\Like;
use App\Hashtag;
use Exception;

class UserProfileServiceObject
{
    private $user;
    private $hashtag;
    private $like;
    private $follower;

    public function __construct(User $user, Hashtag $hashtag, Like $like, Follower $follower)
    {
        $this->user = $user;
        $this->like = $like;
        $this->hashtag = $hashtag;
        $this->follower = $follower;
    }

    public function getUser($username)
    {
        $user;
        try {
            if (Auth::guest() or $username != Auth::user()->username) {
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
                    )
                ->firstOrFail();
            }
            else {
                $user = Auth::user();
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $user;
    }

    public function getFollowerIds($users, $id)
    {
        return $users->where('follows', $id)->pluck('user_id')->toArray();
    }

    public function getFollowingIds($users, $id)
    {
        return $users->where('user_id', $id)->pluck('follows')->toArray();
    }

    public function getFollowers($user, $ids)
    {
        return $this->user->whereIn('id', $ids)
                          ->select('id', 'username', 'name', 'birthdate', 'city', 'country', 'profile_image', 'profile_banner', 'created_at')
                          ->paginate(20);
    }

    public function getTweets($username, $lastid)
    {
        if($lastid != '') {
            try {
                $user = $this->getUser($username);
                $tweets = $user->tweets()
                ->where('id', '<', $lastid)
                ->take(20)
                ->select('id', 'text', 'tweet_image', 'original_image', 'user_id', 'created_at')
                ->get();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        else {
            try {
                $user = $this->getUser($username);
                $tweets = $user->tweets()
                ->take(20)
                ->select('id', 'text', 'tweet_image', 'original_image', 'user_id', 'created_at')
                ->get();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        $posts = [];
        $ids = $tweets->pluck('id')->toArray();
        $tags_collection = $this->hashtag->tweets($ids);
        $likes = $this->like->tweets($ids);

        if(Auth::user()) {
            $liked = $likes->where('user_id', Auth::id())->pluck('tweet_id')->toArray();
        }
        else {
            $liked = -1;
        }

        foreach ($tweets as $tweet) {
            $temp =  $tags_collection->where('tweet_id', $tweet->id)->pluck('tag')->toArray();
            $tags = [];
            foreach ($temp as $tag) {
                array_push($tags, '#'.$tag);
            }
            $tweet->text = str_replace("<br />", "  <br/> ", nl2br(e($tweet->text)));
            $tweet->text = str_replace("\n", " ", $tweet->text);
            $post = array(
                'id' => $tweet->id,
                'text' => explode(" ", $tweet->text),
                'tweet_image' => Config::get("constants.tweet_images").$tweet->tweet_image,
                'original_image' => Config::get("constants.tweet_images").$tweet->original_image,
                'created_at' => $tweet->created_at->timestamp,
                'likes' => $likes->where('tweet_id', $tweet->id)->count(),
                'tags' => $tags,
            );
            array_push($posts, (object)$post);
        }
        return array(
            'posts' => $posts,
            'liked' => $liked,
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'profile_image' => Config::get("constants.avatars").$user->profile_image,
            ],
        );
    }

    public function doesFollow($users, $id)
    {
        $follows = $users->where('user_id', Auth::id());
        $follows = $follows->where('follows', $id);
        if (count($follows) == 0) {
            $follows = false;
        }
        else {
            $follows = true;
        }
        return $follows;
    }

    public function getProfile($username)
    {
        $user = $this->getUser($username);
        $users = $this->follower->users('(user_id = '. $user->id .' or follows = '. $user->id .') and created_at = updated_at');
        $follows = $this->doesFollow($users, $user->id);
        return array(
            'user' => $user,
            'tweet_count' => $user->tweets()->count('tweets.id'),
            'follower_count' => $users->where('follows', $user->id)->count(),
            'following_count' => $users->where('user_id', $user->id)->count(),
            'follows' => $follows,
        );
    }

    public function followers($username)
    {
        $user = $this->getUser($username);
        $users = $this->follower->users('(user_id = '. $user->id .' or follows = '. $user->id .') and created_at = updated_at');
        $follows = $this->doesFollow($users, $user->id);
        return array(
            'user' => $user,
            'people' => $this->getFollowers($user, $this->getFollowerIds($users, $user->id)),
            'following' => $this->getFollowingIds($users, $user->id),
            'tweet_count' => $user->tweets()->count('tweets.id'),
            'follower_count' => $users->where('follows', $user->id)->count(),
            'following_count' => $users->where('user_id', $user->id)->count(),
            'follows' => $follows,
        );
    }

    public function following($username)
    {
        $user = $this->getUser($username);
        $users = $this->follower->users('(user_id = '. $user->id .' or follows = '. $user->id .') and created_at = updated_at');
        $follows = $this->doesFollow($users, $user->id);
        return array(
            'user' => $user,
            'people' => $this->getFollowers($user, $this->getFollowingIds($users, $user->id)),
            'tweet_count' => $user->tweets()->count('tweets.id'),
            'follower_count' => $users->where('follows', $user->id)->count(),
            'following_count' => $users->where('user_id', $user->id)->count(),
            'follows' => $follows,
        );
    }
}

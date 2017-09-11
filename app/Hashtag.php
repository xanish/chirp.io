<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $fillable = [
        'tag', 'tweet_id',
    ];

    public function tweet()
    {
        $this->belongsTo(Tweet::class);
    }

    public function addTag($tag, $tweet_id)
    {
        $this->create([
            'tag' => $tag,
            'tweet_id' => $tweet_id,
        ]);
    }

    public function getTweetsForTag($tag)
    {
        return $this->where('tag', $tag)
            ->join('tweets', 'tweets.id', '=', 'hashtags.tweet_id')
            ->join('users', 'users.id', '=', 'tweets.user_id')
            ->select('users.username', 'users.name', 'users.profile_image', 'tweets.id', 'tweets.text', 'tweets.tweet_image', 'tweets.original_image', 'tweets.created_at')
            ->orderBy('tweets.id', 'DESC')
            ->paginate(20);
    }

    public function findTags($criteria)
    {
        return $this->where('tag', 'LIKE', $criteria.'%')
            ->select('tag')
            ->distinct()
            ->limit(10);
    }

    public function tweets($tweet_ids)
    {
        return $this->whereIn('tweet_id', $tweet_ids)->select('tag', 'tweet_id')->get();
    }

    public function popular()
    {
        return $this->selectRaw('tag, count(tag) as tag_count')->groupBy('tag')->orderBy('tag_count', 'DESC')->limit(10)->get();
    }
}

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
            ->orderBy('tweets.id', 'DESC');
    }

    public function findTags($criteria)
    {
        return $this->where('tag', 'LIKE', '%'.$criteria.'%')
            ->select('tag')
            ->distinct()
            ->limit(10);
    }
}

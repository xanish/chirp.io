<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [
        'text', 'tweet_image', 'original_image', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hashtags()
    {
        return $this->hasMany(Hashtag::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getTags($id)
    {
        return $this->find($id)->hashtags()->pluck('tag')->toArray();
    }

    public function likeCount($id)
    {
        return $this->find($id)->likes()->count();
    }
}

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
}

<?php
namespace App\ServiceObjects;

use App\Hashtag;

class HashtagServiceObject
{
    private $hashtag;

    public function __construct(Hashtag $hashtag)
    {
        $this->hashtag = $hashtag;
    }

    public function addTags($tags, $tweet_id)
    {
        foreach($tags as $tag) {
            $this->hashtag->create([
                'tag' => $tag,
                'tweet_id' => $tweet_id,
            ]);
        }
    }
}

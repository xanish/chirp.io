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
            try {
                $this->hashtag->addTag(ltrim($tag, '#'), $tweet_id);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}

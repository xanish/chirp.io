<?php
namespace App\ServiceObjects;

use Auth;
use \Config;
use App\Tweet;
use App\Utils\Utils;
use Carbon\Carbon;

class TweetServiceObject
{
    private $utils;
    private $tweet;

    public function __construct(Utils $utils, Tweet $tweet)
    {
        $this->utils = $utils;
        $this->tweet = $tweet;
    }

    public function saveTweet($id, $text, $image)
    {
        try {
            $tweet = $this->tweet->create([
                'user_id' => $id,
                'text' => $text,
                'tweet_image' => $image,
                'original_image' => $image == NULL ? $image:'original_'.$image ,
            ]);
        } catch (Exception $e) {
            throw new Exception("Failed To Save Tweet");
        }
        return $tweet->id;
    }

    public function postTweet($request)
    {
        $user = Auth::user();
        try {
            $image_name = $this->createImage($request->tweet_image);
            $tweet_id = $this->saveTweet($user->id, $request->tweet_text, $image_name);
            $request->tweet_text = str_replace("<br />", "  <br/> ", nl2br(e($request->tweet_text)));
            $request->tweet_text = str_replace("\n", " ", $request->tweet_text);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return [
            'tweet_id' => $tweet_id,
            'text' => explode(' ', $request->tweet_text),
            'image' => Config::get("constants.tweet_images").$image_name,
            'original' => Config::get("constants.tweet_images").'original_'.$image_name,
            'date' => Carbon::now()->toDayDateTimeString(),
            'name' => $user->name,
            'username' => $user->username,
            'avatar' => Config::get("constants.avatars").$user->profile_image,
        ];
    }

    public function createImage($image)
    {
        $user = Auth::user();
        $image_name = null;
        if ($image){
            try {
                $image_name = $this->utils->fitAndSaveImage(
                    $user->id,
                    $image,
                    Config::get('constants.tweet_image_width'),
                    Config::get('constants.tweet_image_height'),
                    'tweet_images',
                    'scale-down'
                );
            } catch (Exception $e) {
                throw new Exception("Error Saving Image");
            }
        }
        return $image_name;
    }
}

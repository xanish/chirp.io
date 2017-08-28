<?php
namespace App\ServiceObjects;

use Auth;
use \Config;
use App\Tweet;
use App\Utils\Utils;
use Carbon\Carbon;

class TweetServiceObject
{

  public function saveTweet($id, $text, $image)
  {
    try {
      (new Tweet)->createTweet($id, $text, $image);
    } catch (Exception $e) {
      throw new Exception("Failed To Save Tweet");
    }
  }

  public function postTweet($request)
  {
    $user = Auth::user();
    try {
      $image_name = TweetServiceObject::createImage($request->tweet_image);
      TweetServiceObject::saveTweet($user->id, $request->tweet_text, $image_name);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    return [
      'text' => $request->tweet_text,
      'image' => Config::get("constants.tweet_images").$image_name,
      'date' => Carbon::now()->diffForHumans(),
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
        $image_name = (new Utils)
        ->fitAndSaveImage($user->id, $image, Config::get('constants.tweet_image_width'),
        Config::get('constants.tweet_image_height'), 'tweet_images', 'scale-down');
      } catch (Exception $e) {
        throw new Exception("Error Saving Image");
      }
    }
    return $image_name;
  }
}

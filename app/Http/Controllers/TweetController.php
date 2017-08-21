<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\Utils\Utils;
use Auth;
use \Config;
use Carbon\Carbon;

class TweetController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $tweet = new Tweet;
        $avatar = Config::get("constants.avatars");
        $text = $request->tweet_text;
        $image_name = null;
        if ($request->tweet_image){
            $image_name = (new Utils)->fitAndSaveImage($user->id, $request->tweet_image, Config::get('constants.tweet_image_width'), Config::get('constants.tweet_image_height'), 'tweet_images', 'scale-down');
        }
        $tweet->createTweet($user->id, $text, $image_name);
        if ($image_name != null) {
          $response = '<div class="row padding-20-top-bottom"><div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
          <img class="img-circle img-responsive profile-pic" src="'.$avatar.''.$user->profile_image.'" alt="">
          </div><div class="col-lg-11 col-sm-11 col-md-11 col-sm-11 col-xs-9">
          <b>'.$user->name.'</b>&nbsp;@'.$user->username.'&nbsp; - &nbsp;<span class="grey-text">'.Carbon::now()->diffForHumans().'</span>
          <div class="text">'.$text.'</div><div class="image padding-20 hidden-xs">
              <img class="img-responsive" src="'.Config::get('constants.tweet_images').''.$image_name.'" alt="">
          </div></div><div class="image col-xs-12 visible-xs">
          <img class="img-responsive" src="'.Config::get('constants.tweet_images').''.$image_name.'" alt=""></div></div>';
        }
        else {
          $response = '<div class="row padding-20-top-bottom"><div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
          <img class="img-circle img-responsive profile-pic" src="'.$avatar.''.$user->profile_image.'" alt="">
          </div><div class="col-lg-11 col-sm-11 col-md-11 col-sm-11 col-xs-9">
          <b>'.$user->name.'</b>&nbsp;@'.$user->username.'&nbsp; - &nbsp;<span class="grey-text">'.Carbon::now()->diffForHumans().'</span>
          <div class="text">'.$text.'</div></div></div>';
        }
        return response()->json([
          'element' => $response
        ]);
    }
}

<?php

namespace App;

use \Config;
use Carbon\Carbon;

class Formatter {

  public function changeFormatToSearchItem($data)
  {
    $response = '';
    $avatar = Config::get('constants.avatars');
    foreach ($data as $item) {
      $response .= '<li><div class="row item-row search-item"><div class="col-xs-2"><img class="img-responsive img-circle" src="'.$avatar.''.$item->profile_image.'" alt="">
      </div><div class="col-xs-10"><a href="/'.$item->username.'"><span><b>'.$item->name.'</b> <span>@'.$item->username.'</span></span></a></div></div></li>';
    }
    return $response;
  }

  public function changeFormatToTweet($user, $text, $image_name=null)
  {
    $response = '';
    $avatar = Config::get('constants.avatars');
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
    return $response;
  }
}
